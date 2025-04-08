<?php

namespace App\Service;

interface SluggerInterface
{
    /**
     * Génère un slug à partir d'une chaîne de caractères.
     *
     * @param string $string La chaîne de caractères à "slugifier".
     * @return string Le slug généré.
     */
    public function slugify(string $string): string;
}