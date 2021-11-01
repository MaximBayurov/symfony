<?php

namespace App\Service;

class LimitOptions
{
    
    public function get(int $limit): array
    {
    
        $limitOptions = [
            [
                'value' => 10,
                'text' => 'Десять',
                'selected' => false,
            ],
            [
                'value' => 20,
                'text' => 'Двадцать',
                'selected' => false,
            ],
            [
                'value' => 50,
                'text' => 'Пятьдесят',
                'selected' => false,
            ],
        ];
    
        array_walk($limitOptions, function (&$item) use ($limit) {
            $item['selected'] = $limit === $item['value'];
        });
        return $limitOptions;
    }
}