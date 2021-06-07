<?php

namespace Src\Controllers;

use Src\App\Database\Connection;

class DictionaryController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];

        $sql = "SELECT id_dictionary, title_dictionary as title FROM dictionaries WHERE id_user = :id_user";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_dictionary = $data['id_dictionary'];

        $sql = "DELETE FROM dictionaries WHERE id_user = :id_user AND id_dictionary = :id_dictionary";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_dictionary' => $id_dictionary
        ]);
    }
}