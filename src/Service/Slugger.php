<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface as SymfonySluggerInterface;

class Slugger implements SluggerInterface
{
    private SymfonySluggerInterface $slugger;

    public function __construct(SymfonySluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function slugify(string $string): string
    {
        return $this->slugger->slug($string)->lower()->toString();
    }
}