<?php


namespace App\Services;


use Doctrine\Migrations\Configuration\Exception\FileNotFound;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService implements FileServiceInterface
{
    private SluggerInterface $slugger;


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $path): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->slugger->slug($originalFilename) .'-' . uniqid() .'.' .$file->guessExtension();

        $file->move($path, $filename);

        return $filename;
    }


    public function delete(string $file, string $path): void
    {
        if (!file_exists($path)){
            throw new FileException("Le répertoire n'existe pas");
        }

        if (!file_exists($path . DIRECTORY_SEPARATOR . $file)){
            throw new FileNotFound("le fichier n'existe pas");
        }

        unlink($path . DIRECTORY_SEPARATOR .$file);
    }
}