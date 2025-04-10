<?php

namespace App\Service;

class ImageService
{
    public function convertToWebP(string $sourcePath, string $destinationPath): void
    {
        ini_set('memory_limit', '512M'); // Augmente temporairement la limite de mémoire

        $imageInfo = getimagesize($sourcePath);
        $mimeType = $imageInfo['mime'];

        // Charger l'image en fonction de son type MIME
        if ($mimeType === 'image/jpeg') {
            $image = imagecreatefromjpeg($sourcePath);
        } elseif ($mimeType === 'image/png') {
            $image = imagecreatefrompng($sourcePath);
        } else {
            throw new \Exception('Format d\'image non supporté pour la conversion en WebP.');
        }

        // Redimensionner l'image si elle est trop grande (par exemple, max 1920x1080)
        $maxWidth = 1920;
        $maxHeight = 1080;
        if (imagesx($image) > $maxWidth || imagesy($image) > $maxHeight) {
            $image = $this->resizeImage($image, $maxWidth, $maxHeight);
        }

        // Convertir en WebP
        imagewebp($image, $destinationPath);

        // Libérer la mémoire
        imagedestroy($image);
    }

    public function resizeImage($image, int $maxWidth, int $maxHeight)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Calculer les nouvelles dimensions tout en conservant le ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        // Créer une nouvelle image redimensionnée
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $newImage;
    }
}