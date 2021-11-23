<?php

namespace App\Homework;


class RegistrationSpamFilter
{
    public function filter(string $email): bool 
    {
        $domain = mb_substr($email, mb_strrpos($email, '.'));
        
        return ! in_array($domain, ['.ru', '.com', '.org']);
    }
}
