<?php

namespace App\Service;

class Parsedown extends \Demontpx\ParsedownBundle\Parsedown
{
    public function text($text)
    {
        return "Внимание! Внимание!\n" . parent::text($text);
    }
}