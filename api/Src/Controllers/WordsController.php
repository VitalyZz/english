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

        $sql = "SELECT w.word as word, wi.id_word_information as id_word_information, (SELECT group_concat(distinct (SELECT t.translation FROM translations t WHERE t.id_translation = ws.id_translation)) from word_stock ws where ws.id_word = wi.id_word) as translation, wi.id_dictionary as id_dictionary, wi.correct as correct, wi.wrong as wrong, wi.temporal_number_correct as temporal_number_correct, wi.repetitions as repetitions, d.title_dictionary as title
                    FROM words_information wi
                        INNER JOIN words w ON wi.id_word = w.id_word
                            LEFT JOIN dictionaries d on wi.id_dictionary = d.id_dictionary
                                WHERE wi.id_user = :id_user AND wi.id_dictionary = :id_dictionary";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_dictionary' => $id_dictionary,
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByMeaningWithTranslations() :array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $meaning = $data['meaning'];

        $sql = "SELECT w.word as word, wi.id_word_information as id_word_information, (SELECT group_concat(distinct (SELECT t.translation FROM translations t WHERE t.id_translation = ws.id_translation)) from word_stock ws where ws.id_word = wi.id_word) as translation, wi.id_dictionary as id_dictionary, wi.correct as correct, wi.wrong as wrong, wi.temporal_number_correct as temporal_number_correct, wi.repetitions as repetitions, d.title_dictionary as title
                    FROM words_information wi
                        INNER JOIN words w ON wi.id_word = w.id_word
                            LEFT JOIN dictionaries d ON wi.id_dictionary = d.id_dictionary
                                WHERE wi.id_user = :id_user AND wi.meaning = :meaning";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':meaning' => $meaning,
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByMeaning() :array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $meaning = $data['meaning'];

        $sql = "SELECT w.word as word, w.id_word as id_word
                    FROM words_information wi
                        INNER JOIN words w ON wi.id_word = w.id_word 
                            WHERE id_user = :id_user AND meaning = :meaning";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':meaning' => $meaning,
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_dictionary = $data['id_dictionary'];
        $id_word_information = $data['id_word_information'];

        $place_holders = implode(',', array_fill(0, count($id_word_information), '?'));

        $sql = "DELETE FROM words_information WHERE id_user = ? AND id_dictionary = ? AND id_word_information IN ($place_holders)";

        array_unshift($id_word_information, $id_dictionary);
        array_unshift($id_word_information, $id_user);

        $statement = $this->connection->prepare($sql);
        $statement->execute($id_word_information);
    }

    public function deleteWithoutDictionaryById(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_word_information = $data['id_word_information'];

        $place_holders = implode(',', array_fill(0, count($id_word_information), '?'));

        $sql = "DELETE FROM words_information WHERE id_user = ? AND id_word_information IN ($place_holders)";

        array_unshift($id_word_information, $id_user);

        $statement = $this->connection->prepare($sql);
        $statement->execute($id_word_information);
    }

    public function deleteWithoutDictionary(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_word = $data['id_word'];

        $sql = "DELETE FROM words_information WHERE id_user = :id_user AND id_word = :id_word";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
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

    public function getStudyWords() :array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];

        $sql = "SELECT wi.id_word_information as id_word_information, wi.id_word as id_word, w.word as word, group_concat(distinct t.translation) as translations, wi.temporal_number_correct as temporal_number_correct, wi.correct as correct, wi.wrong as wrong, wi.repetitions as repetitions
                FROM word_stock ws
                    INNER JOIN words w on ws.id_word = w.id_word
                        INNER JOIN translations t on ws.id_translation = t.id_translation
                            INNER JOIN words_information wi on ws.id_word = wi.id_word
                                WHERE id_user = :id_user AND (wi.temporal_number_correct < 5 OR wi.temporal_number_correct is null)
                                    GROUP BY word";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAll() :array
    {
        $sql = "SELECT u.word, u.id_word
                    FROM words u
                        LEFT JOIN word_stock ws ON u.id_word = ws.id_word
                            WHERE ws.id_word IS NULL
                                ORDER BY u.id_word LIMIT 100";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllCountOfWords() :array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];

        $sql = "SELECT COUNT(id_word) as words, SUM(correct) as correct, SUM(wrong) as wrong FROM words_information WHERE id_user = :id_user";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function changeMeaning(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $meaning = $data['meaning'];
        $id_user = $data['id_user'];
        $id_word = $data['id_word'];
        $word = $data['word'];
        $translations = $data['translations'];

        if ($id_word == null) {
            $sql = "SELECT id_word FROM words WHERE word = :word";

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':word' => $word
            ]);

            $result = $statement->fetch(\PDO::FETCH_ASSOC);

            if ($result) {
                $id_word = $result['id_word'];
            }

//            var_dump($result);

            if (!$result) {
                $sql = "INSERT INTO words(`word`) VALUES(:word)";

                $statement = $this->connection->prepare($sql);
                $statement->execute([
                    ':word' => $word
                ]);

                $id_word = $this->connection->lastInsertId();

                // Добавление перевода в цикле

                foreach ($translations as $translation) {
                    $sql = "SELECT id_translation, translation FROM translations WHERE translation = :translation";

                    $statement = $this->connection->prepare($sql);
                    $statement->execute([
                        ':translation' => $translation,
                    ]);

                    $result = $statement->fetch(\PDO::FETCH_ASSOC);

                    if ($result) {
                        $id_translation = $result['id_translation'];

                        $sql = "INSERT INTO word_stock(`id_translation`, `id_word`) VALUES(:id_translation, :id_word)";

                        $statement = $this->connection->prepare($sql);
                        $statement->execute([
                            ':id_translation' => $id_translation,
                            ':id_word' => $id_word,
                        ]);
                    } else {
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
                }
            }

            $sql = "INSERT INTO words_information(`id_user`, `id_word`, `meaning`) VALUES(:id_user, :id_word, :meaning)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':meaning' => $meaning,
                ':id_user' => $id_user,
                ':id_word' => $id_word
            ]);
        } else {
            $sql = "UPDATE words_information SET meaning = :meaning WHERE id_user = :id_user AND id_word = :id_word";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':meaning' => $meaning,
                ':id_user' => $id_user,
                ':id_word' => $id_word
            ]);
        }
    }

    public function update(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $repetitions = $data['repetitions'];
        $wrong = $data['wrong'];
        $correct = $data['correct'];
        $temporal_number_correct = $data['temporal_number_correct'];
        $id_user = $data['id_user'];
        $id_word_information = $data['id_word_information'];

        $sql = "UPDATE words_information 
                    SET repetitions = :repetitions, wrong = :wrong, correct = :correct, temporal_number_correct = :temporal_number_correct
                        WHERE id_user = :id_user AND id_word_information = :id_word_information";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':repetitions' => $repetitions,
            ':wrong' => $wrong,
            ':correct' => $correct,
            ':temporal_number_correct' => $temporal_number_correct,
            ':id_user' => $id_user,
            ':id_word_information' => $id_word_information,
        ]);
    }

    public function updateByChangeDictionary(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_word_information = $data['id_word_information'];
        $id_dictionary = $data['id_dictionary'];

        $place_holders = implode(',', array_fill(0, count($id_word_information), '?'));

        $sql = "UPDATE words_information 
                    SET id_dictionary = ?
                        WHERE id_user = ? AND id_word_information IN ($place_holders)";

        array_unshift($id_word_information, $id_user);
        array_unshift($id_word_information, $id_dictionary);

        $statement = $this->connection->prepare($sql);
        $statement->execute($id_word_information);
    }

    public function getIdByWord(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $answer = $data['answer'];

        $sql = "SELECT wi.id_word_information as id_word_information
                    FROM words_information wi
                        INNER JOIN words w on wi.id_word = w.id_word
                            WHERE id_user = :id_user AND w.word = :answer";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':answer' => $answer
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}