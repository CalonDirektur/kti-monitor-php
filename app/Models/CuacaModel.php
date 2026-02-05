<?php

namespace App\Models;

class CuacaModel extends BaseModel
{
    protected $table = 'cuaca';
    
    /**
     * Get all weather data
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY wilayah, tanggal";
        return $this->query($sql);
    }
    
    /**
     * Get today's weather
     */
    public function getToday()
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE DATE(tanggal) = CURDATE() 
                ORDER BY wilayah, jam";
        return $this->query($sql);
    }
    
    /**
     * Get weather by region
     */
    public function getByWilayah($wilayah)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE wilayah LIKE ? 
                ORDER BY tanggal, jam";
        return $this->query($sql, ["%{$wilayah}%"]);
    }
    
    /**
     * Get all unique locations
     */
    public function getAllLocations()
    {
        $sql = "SELECT DISTINCT wilayah, latitude, longitude 
                FROM {$this->table} 
                WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
        return $this->query($sql);
    }
    
    /**
     * Save weather data
     */
    public function save($data)
    {
        if (is_array($data) && isset($data[0])) {
            // Bulk insert
            foreach ($data as $item) {
                $this->upsert($item);
            }
            return true;
        }
        
        return $this->upsert($data);
    }
    
    /**
     * Upsert weather data
     */
    protected function upsert($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (wilayah, tanggal, jam, suhu, kelembaban, cuaca, angin_kecepatan, angin_arah, latitude, longitude, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE
                suhu = VALUES(suhu),
                kelembaban = VALUES(kelembaban),
                cuaca = VALUES(cuaca),
                angin_kecepatan = VALUES(angin_kecepatan),
                angin_arah = VALUES(angin_arah),
                updated_at = NOW()";
        
        return $this->execute($sql, [
            $data['wilayah'] ?? null,
            $data['tanggal'] ?? null,
            $data['jam'] ?? null,
            $data['suhu'] ?? null,
            $data['kelembaban'] ?? null,
            $data['cuaca'] ?? null,
            $data['angin_kecepatan'] ?? null,
            $data['angin_arah'] ?? null,
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
        ]);
    }
    
    /**
     * Get weather forecast for location
     */
    public function getForecast($wilayah, $days = 3)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE wilayah = ? AND tanggal >= CURDATE() 
                AND tanggal <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY tanggal, jam";
        return $this->query($sql, [$wilayah, $days]);
    }
}
