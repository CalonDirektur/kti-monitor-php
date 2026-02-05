<?php

namespace App\Models;

class UdaraModel extends BaseModel
{
    protected $table = 'kualitas_udara';
    
    /**
     * Get all air quality data
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY lokasi, tanggal DESC";
        return $this->query($sql);
    }
    
    /**
     * Get latest air quality data
     */
    public function getLatest()
    {
        $sql = "SELECT u1.* FROM {$this->table} u1
                INNER JOIN (
                    SELECT lokasi, MAX(created_at) as max_created
                    FROM {$this->table}
                    GROUP BY lokasi
                ) u2 ON u1.lokasi = u2.lokasi AND u1.created_at = u2.max_created
                ORDER BY u1.aqi DESC";
        return $this->query($sql);
    }
    
    /**
     * Get air quality by location
     */
    public function getByLocation($lokasi)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE lokasi = ? 
                ORDER BY tanggal DESC, jam DESC 
                LIMIT 24";
        return $this->query($sql, [$lokasi]);
    }
    
    /**
     * Save air quality data
     */
    public function save($data)
    {
        if (is_array($data) && isset($data[0])) {
            foreach ($data as $item) {
                $this->insert($item);
            }
            return true;
        }
        
        return $this->insert($data);
    }
    
    /**
     * Get unhealthy locations
     */
    public function getUnhealthyLocations($minAqi = 100)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE aqi >= ? 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ORDER BY aqi DESC";
        return $this->query($sql, [$minAqi]);
    }
    
    /**
     * Get AQI category
     */
    public static function getAqiCategory($aqi)
    {
        if ($aqi <= 50) return ['label' => 'Baik', 'color' => '#00e400'];
        if ($aqi <= 100) return ['label' => 'Sedang', 'color' => '#ffff00'];
        if ($aqi <= 150) return ['label' => 'Tidak Sehat untuk Sensitif', 'color' => '#ff7e00'];
        if ($aqi <= 200) return ['label' => 'Tidak Sehat', 'color' => '#ff0000'];
        if ($aqi <= 300) return ['label' => 'Sangat Tidak Sehat', 'color' => '#8f3f97'];
        return ['label' => 'Berbahaya', 'color' => '#7e0023'];
    }
}
