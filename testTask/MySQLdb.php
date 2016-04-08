<?php

require_once 'mysql.php';

class MySQLdb
{
    private $pdo;
    private static $instance;

    public static function init($dbName, $host, $user, $password)
    {
        if (!self::$instance) {
            self::$instance = new self($dbName, $host, $user, $password);
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            throw new Exception('MySQLdb not init, use init method.');
        }
        return self::$instance;
    }

    private function __construct($dbName, $host, $user, $password)   // при необходимости $password
    {
        try {
            $this->pdo = new PDO("mysql:dbname=$dbName;host=$host", $user);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getComments()
    {
        $s = $this->pdo->prepare('SELECT * FROM comment');
        if ($s->execute()) {
            if ($comments = $s->fetchAll(PDO::FETCH_ASSOC)) {
                return $comments;
            }
        }
        return [];
    }

    public function addComment($name, $email, $comment, $photoURI)
    {
        $s = $this->pdo->prepare('INSERT INTO comment (name, email, comment, photoURI, date) VALUES (:name, :email, :comment, :photoURI, NOW())');
        $s->bindValue(':name', $name);
        $s->bindValue(':email', $email);
        $s->bindValue(':comment', $comment);
        $s->bindValue('photoURI', $photoURI);
        $s->execute();
        return $this->pdo->lastInsertId();
    }
}