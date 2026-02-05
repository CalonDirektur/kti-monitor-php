<?php

namespace App\Helpers;

class DateHelper
{
    public static function formatIndonesia($date, $withTime = false)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $timestamp = strtotime($date);
        $day = date('d', $timestamp);
        $month = $months[(int) date('m', $timestamp)];
        $year = date('Y', $timestamp);
        
        $result = "{$day} {$month} {$year}";
        
        if ($withTime) {
            $result .= ' ' . date('H:i', $timestamp) . ' WIB';
        }
        
        return $result;
    }
    
    public static function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
        if ($diff < 604800) return floor($diff / 86400) . ' hari yang lalu';
        
        return self::formatIndonesia($datetime);
    }
    
    public static function toIndonesiaTimezone($datetime)
    {
        $dt = new \DateTime($datetime, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone('Asia/Jakarta'));
        return $dt->format('Y-m-d H:i:s');
    }
}
