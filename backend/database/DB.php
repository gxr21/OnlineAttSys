<?php

class DB
{
    protected $table;
    protected $pdo;
    protected $attributes = [];
    protected $results = []; 

    public function __construct($table)
    {
        $this->table = $table;

        $dsn = 'mysql:host=localhost;dbname=attendance_db;charset=utf8';
        $username = 'root';
        $password = '';
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function fill($data)
    {
        $this->attributes = $data;
    }

    public function find($conditions)
    {
        if (is_numeric($conditions)) {
            $conditions = ['id' => $conditions];
        }
        $queryParts = [];
        foreach ($conditions as $key => $value) {
            $queryParts[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE " . implode(' AND ', $queryParts));
        $query->execute($conditions);
        $this->results = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($this->results) {
            $this->fill($this->results[0]);
        }
        return $this;
    }

    public function get()
    {
        return $this->results;
    }

    public function all()
    {
        $q = $this->pdo->prepare("SELECT * FROM {$this->table}");
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $query->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function delete()
    {
        if (!isset($this->attributes['id'])) {
            throw new Exception('Cannot delete a record without an ID.');
        }

        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $result = $query->execute([$this->attributes['id']]);

        if ($result) {
            $this->attributes = [];
        }

        return $result;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
}
