<?php


namespace App\Service;


class Slugify
{
    public function generate(string $slug) : string
    {
        if(!empty($slug)) {
            $input= str_replace("-", " ", $slug);
        }

    }
}