<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    public function upload(UploadedFile $file, string $targetDirectory, string $fileName): string
    {
        $file->move($targetDirectory, $fileName); // Déplace le fichier dans le répertoire de destination
        return $fileName;// Retourne le nom du fichier
    }

}