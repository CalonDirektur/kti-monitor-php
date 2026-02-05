<?php

namespace App\Models;

class GempaModel extends BaseModel
{
    protected $table = 'gempa';
    
    /**
     * Get all earthquake data
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY tanggal DESC, jam DESC";
        return $this->query($sql);
    }
    
    /**
     * Get latest earthquake data
     */
    public function getLatest($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY tanggal DESC, jam DESC LIMIT ?";
        return $this->query($sql, [$limit]);
    }
    
    /**
     * Get recent earthquakes (within specified minutes)
     */
    public function getRecent($minutes = 10)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE) 
                ORDER BY created_at DESC";
        return $this->query($sql, [$minutes]);
    }
    
    /**
     * Save earthquake data if it's new
     */
    public function saveIfNew($data)
    {
        // Check if already exists
        $sql = "SELECT id FROM {$this->table} 
                WHERE tanggal = ? AND jam = ? AND coordinates = ?";
        $existing = $this->query($sql, [
            $data['tanggal'],
            $data['jam'],
            $data['coordinates']
        ]);
        
        if (empty($existing)) {
            return $this->insert($data);
        }
        
        return false;
    }
    
    /**
     * Get earthquakes by magnitude range
     */
    public function getByMagnitude($min, $max = null)
    {
        if ($max) {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE magnitude BETWEEN ? AND ? 
                    ORDER BY tanggal DESC, jam DESC";
            return $this->query($sql, [$min, $max]);
        }
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE magnitude >= ? 
                ORDER BY tanggal DESC, jam DESC";
        return $this->query($sql, [$min]);
    }
    
    /**
     * Get earthquakes for map display
     */
    public function getForMap()
    {
        $sql = "SELECT id, tanggal, jam, magnitude, kedalaman, wilayah, 
                       coordinates, lintang, bujur, potensi 
                FROM {$this->table} 
                ORDER BY tanggal DESC, jam DESC 
                LIMIT 100";
        return $this->query($sql);
    }
}
