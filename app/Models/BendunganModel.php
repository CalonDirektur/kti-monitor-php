<?php

namespace App\Models;

class BendunganModel extends BaseModel
{
    protected $table = 'bendungan';
    
    /**
     * Get all dam data
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nama";
        return $this->query($sql);
    }
    
    /**
     * Get dam by ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }
    
    /**
     * Get dam status
     */
    public function getWithStatus()
    {
        $sql = "SELECT b.*, 
                    CASE 
                        WHEN (volume_saat_ini / kapasitas_total * 100) >= 90 THEN 'siaga'
                        WHEN (volume_saat_ini / kapasitas_total * 100) >= 75 THEN 'waspada'
                        ELSE 'normal'
                    END as status
                FROM {$this->table} b
                ORDER BY status DESC, nama";
        return $this->query($sql);
    }
    
    /**
     * Get dams in alert status
     */
    public function getAlertStatus()
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (volume_saat_ini / kapasitas_total * 100) >= 75
                ORDER BY (volume_saat_ini / kapasitas_total) DESC";
        return $this->query($sql);
    }
    
    /**
     * Update dam water level
     */
    public function updateWaterLevel($id, $volume)
    {
        $sql = "UPDATE {$this->table} 
                SET volume_saat_ini = ?, 
                    updated_at = NOW() 
                WHERE id = ?";
        return $this->execute($sql, [$volume, $id]);
    }
    
    /**
     * Get dam history
     */
    public function getHistory($id, $days = 7)
    {
        $sql = "SELECT * FROM bendungan_history 
                WHERE bendungan_id = ? 
                AND tanggal >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                ORDER BY tanggal DESC, jam DESC";
        return $this->query($sql, [$id, $days]);
    }
    
    /**
     * Get dams for map display
     */
    public function getForMap()
    {
        $sql = "SELECT id, nama, lokasi, latitude, longitude, 
                       kapasitas_total, volume_saat_ini,
                       (volume_saat_ini / kapasitas_total * 100) as persentase
                FROM {$this->table}
                WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
        return $this->query($sql);
    }
}
