<?php

namespace App\Homework;

class RegistrationSpamFilter
{
    const ALLOWED_DOMAINS = [
        'ru',
        'com',
        'org',
    ];
    
    public function filter(string $email): bool
    {
        $pieces = explode('.', $email);
        $domain = end($pieces);
        return !in_array($domain, self::ALLOWED_DOMAINS);
    }
}