<?php

namespace Src\Controllers;

use Src\App\Database\Connection;
use Src\App\Storage\SessionStorage;

class AuthController
{
    /**
     * @var Connection
     */
    private Connection $connection;
    /**
     * @var SessionStorage
     */
    private SessionStorage $storage;

    public function __construct(Connection $connection, SessionStorage $storage)
    {
        $this->connection = $connection;
        $this->storage = $storage;
    }

    public function login(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $email = $data['email'];
        $password = $data['password'];

        $sql = "SELECT email, r.role_name as role, password, id_user as id
                    FROM users u
                        INNER JOIN roles r on u.id_role = r.id_role
                            WHERE email = '$email'";
        $result = $this->connection->query($sql)->fetch(\PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {

            $this->storage->set('user', [
                'id' => $result['id'],
                'email' => $result['email'],
                'role' => $result['role']
            ]);

            return [
                'id' => $result['id'],
                'email' => $result['email'],
                'role' => $result['role']
            ];
        }

        return [];
    }

    public function signup(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $email = $data['email'];
        $password = $data['password'];
        $password_confirm = $data['password_confirm'];

        if ($password == $password_confirm) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(`email`, `password`) VALUES (:email, :password)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':email' => $email,
                ':password' => $password_hash
            ]);

            $this->storage->set('user', [
                'id' => $this->connection->lastInsertId(),
                'email' => $email,
                'role' => 1
            ]);

            return [
                'id' => $this->connection->lastInsertId(),
                'email' => $email,
                'role' => 1
            ];
        }

        return [];
    }

    public function logout(): bool
    {
        $this->storage->delete();
        return true;
    }

    public function check()
    {
        return $_SESSION;
//        $this->storage->has('user');
    }
}