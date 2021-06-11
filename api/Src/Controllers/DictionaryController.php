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

    public function statistics(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_dictionary = $data['id_dictionary'];

        if (in_array($id_dictionary, ['known', 'study'])) {
            $values = [
                'known' => 0,
                'study' => 1
            ];

            $meaning = $values[$id_dictionary];

            $sql = "SELECT SUM(repetitions) as repetitions, SUM(correct) as correct, SUM(wrong) as wrong, COUNT(id_word) as words
                        FROM words_information 
                            WHERE id_user = :id_user AND meaning = :meaning";

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':id_user' => $id_user,
                ':meaning' => $meaning
            ]);
        } else {
            $sql = "SELECT SUM(repetitions) as repetitions, SUM(correct) as correct, SUM(wrong) as wrong, (SELECT COUNT(meaning) FROM words_information WHERE meaning = 0 AND id_user = :id_user AND id_dictionary = :id_dictionary) as known, (SELECT COUNT(meaning) FROM words_information WHERE meaning = 1 AND id_user = :id_user AND id_dictionary = :id_dictionary) as study, COUNT(id_word) as words
                        FROM words_information
                            WHERE id_user = :id_user AND id_dictionary = :id_dictionary";

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':id_user' => $id_user,
                ':id_dictionary' => $id_dictionary
            ]);

        }

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $title = $data['title'];

        $sql = "INSERT INTO dictionaries(`id_user`, `title_dictionary`) VALUES (:id_user, :title_dictionary)";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':title_dictionary' => $title,
        ]);
    }

    public function delete()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_dictionary = $data['id_dictionary'];

        $sql = "UPDATE words_information SET id_dictionary = null WHERE id_user = :id_user AND id_dictionary = :id_dictionary";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_dictionary' => $id_dictionary
        ]);

        $sql = "DELETE FROM dictionaries WHERE id_user = :id_user AND id_dictionary = :id_dictionary";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_dictionary' => $id_dictionary
        ]);
    }
}