<?php

require_once 'config.php';

class Database
{
    private $pdo;
    private static $db;

    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . DB_HOST . '; dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOEXception $e) {
            $_SESSION['errors'][] = 'Ошибка при подключении к БД: ' . $e->getMessage();
        }
    }

    public static function getDBO()
    {
        if (!self::$db) self::$db = new Database();
        return self::$db;
    }


    public function getRowByWhere(string $table_name, string $where, array $values): mixed
    {
        $query = 'SELECT * FROM ' . $table_name . ' WHERE' . $where;
        $query = $this->pdo->prepare($query);
        $query->execute($values);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (is_array($result)) return $result;
        return 'Пользовательв в БД не найден';
    }

    public function getRowByEmail(string $table_name, string $email)
    {
        $where = " `email`=? ";
        return $this->getRowByWhere($table_name, $where, [$email]);
    }


    public function updateByEmail(string $table_name, array $fields, array $values): bool
    {
        $query = 'UPDATE ' . $table_name . ' SET ';
        foreach ($fields as $field) {
            $query .= "$field = ?,";
        }
        $query = substr($query, 0, -1);
        $query = $query . ' WHERE `email` = ? ';
        $query = $this->pdo->prepare($query);
        return $query->execute($values);
    }


    public function insert(string $table_name, array $fields, array $insert_values): bool
    {
        $query = 'INSERT ' . "`$table_name`" . ' (';
        foreach ($fields as $field) {
            $query .= "$field, ";
        }
        $query = substr($query, 0, -2);
        $query = $query . ') VALUES ( ';
        foreach ($insert_values as $value) {
            $query .= "?, ";
        }
        $query = substr($query, 0, -2);
        $query = $query . ');';

        $query = $this->pdo->prepare($query);
        return $query->execute($insert_values);
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}

?>