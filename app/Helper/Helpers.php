<?php

namespace App\Helper;

class Helpers
{
    /**
     * Hitung jarak antara dua koordinat (meter) menggunakan rumus Haversine.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float meters
     */
    public static function jarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371000.0; // radius bumi dalam meter
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $dPhi = deg2rad($lat2 - $lat1);
        $dLambda = deg2rad($lon2 - $lon1);

        $a = sin($dPhi / 2) * sin($dPhi / 2) +
             cos($phi1) * cos($phi2) *
             sin($dLambda / 2) * sin($dLambda / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }

    /**
     * Cek apakah dua koordinat berada dalam radius (meter).
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @param float $radiusMeters
     * @return bool
     */
    public static function dalamRadius(float $lat1, float $lon1, float $lat2, float $lon2, float $radiusMeters): bool
    {
        return self::jarak($lat1, $lon1, $lat2, $lon2) <= $radiusMeters;
    }
}