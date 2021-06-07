<?php

namespace Src\Controllers;

use Src\App\Database\Connection;

class WordsController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByDictionaryId(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_dictionary = $data['id_dictionary'];

        $sql = "SELECT id_user, id_dictionary, id_word, repetitions, last_repetition, correct, wrong, meaning, temporal_number_correct FROM words_information WHERE id_user = :id_user AND id_dictionary = :id_dictionary";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_dictionary' => $id_dictionary,
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByMeaning() :array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $meaning = $data['meaning'];

        $sql = "SELECT id_user, id_dictionary, id_word, repetitions, last_repetition, correct, wrong, meaning, temporal_number_correct FROM words_information WHERE id_user = :id_user AND meaning = :meaning";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':meaning' => $meaning,
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_dictionary = $data['id_dictionary'];
        $id_word = $data['id_word'];

        $sql = "DELETE FROM words_information WHERE id_user = :id_user AND id_dictionary = :id_dictionary AND id_word = :id_word";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_dictionary' => $id_dictionary,
            ':id_word' => $id_word
        ]);
    }

    public function insertTranslation()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $translation = $data['translation'];
        $id_word = $data['id_word'];

        $sql = "INSERT INTO translations(`translation`) VALUES(:translation)";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':translation' => $translation,
        ]);

        $id_translation = $this->connection->lastInsertId();

        $sql = "INSERT INTO word_stock(`id_translation`, `id_word`) VALUES(:id_translation, :id_word)";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_translation' => $id_translation,
            ':id_word' => $id_word,
        ]);
    }

    public function getAll() :array
    {
        $sql = "SELECT id_word, word FROM words LIMIT 10";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}