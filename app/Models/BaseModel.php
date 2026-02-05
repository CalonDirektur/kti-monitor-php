<?php

namespace App\Models;

use PDO;
use PDOException;

class BaseModel
{
    protected $pdo;
    protected $table;
    
    public function __construct()
    {
        $this->connect();
    }
    
    /**
     * Create database connection
     */
    protected function connect()
    {
        $config = require APP_PATH . '/Config/database.php';
        $db = $config['connections'][$config['default']];
        
        try {
            $dsn = "{$db['driver']}:host={$db['host']};port={$db['port']};dbname={$db['database']};charset={$db['charset']}";
            
            $this->pdo = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Execute a query and return results
     */
    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Execute a query without returning results
     */
    protected function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new \Exception('Execute failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Insert a record
     */
    protected function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        if ($this->execute($sql, array_values($data))) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update a record
     */
    protected function update($id, $data)
    {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = ?";
        
        $params = array_values($data);
        $params[] = $id;
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Delete a record
     */
    protected function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    /**
     * Find a record by ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }
    
    /**
     * Get PDO instance
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
