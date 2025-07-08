<?php

namespace App\Utils;

class Device
{
    public static function execute(string $userAgent, bool $stringResponse = false): string|array
    {
        $mobileOSPatterns = [
            'iOS' => '/(iPhone|iPad|iPod)/i',
            'Android' => '/Android/i',
            'Windows Phone' => '/Windows Phone/i',
            'BlackBerry' => '/BlackBerry|BB10/i',
        ];

        $isMobile = false;
        $os = 'Unknown';

        foreach ($mobileOSPatterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $isMobile = true;
                $os = $name;
                break;
            }
        }

        $device = $isMobile ? 'Mobile' : 'Desktop';
        $os = $isMobile ? $os : 'Desktop';

        if ($stringResponse) {

            return "$device /  $os";
        }

        return [
            'device' => $device,
            'os' => $os,
        ];
    }
}
