<?php

namespace App\Helpers;

class PageHelper
{
    public static function labels(): array
    {
        $labels = [
            'landing' => 'Landing Page',
            'offer'   => 'Offer Page',
        ];

        ksort($labels); // ASC
        return $labels;
    }
}
