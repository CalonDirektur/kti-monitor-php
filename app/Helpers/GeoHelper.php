<?php

namespace App\Helpers;

class GeoHelper
{
    public static function parseCoordinates($coordString)
    {
        $parts = explode(',', $coordString);
        return [
            'lat' => (float) trim($parts[0] ?? 0),
            'lng' => (float) trim($parts[1] ?? 0),
        ];
    }
    
    public static function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    public static function isInIndonesia($lat, $lng)
    {
        return $lat >= -11 && $lat <= 6 && $lng >= 95 && $lng <= 141;
    }
    
    public static function formatDMS($decimal, $isLat = true)
    {
        $direction = $isLat 
            ? ($decimal >= 0 ? 'LU' : 'LS')
            : ($decimal >= 0 ? 'BT' : 'BB');
        
        $decimal = abs($decimal);
        $degrees = floor($decimal);
        $minutes = floor(($decimal - $degrees) * 60);
        $seconds = round((($decimal - $degrees) * 60 - $minutes) * 60, 2);
        
        return "{$degrees}°{$minutes}'{$seconds}\" {$direction}";
    }
}
