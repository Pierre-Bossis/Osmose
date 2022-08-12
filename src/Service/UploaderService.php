<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Httpfoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class UploaderService
{
    //On va lui passer un objet de type UploadedFile
    //doit retourner le nom de ce file

    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    public function uploadFile(UploadedFile $file, string $directoryFolder)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try{
            $file->move(
               $directoryFolder,
                $newFilename
            );
        } catch (FileException $e){

        }

        return $newFilename;
    }
}