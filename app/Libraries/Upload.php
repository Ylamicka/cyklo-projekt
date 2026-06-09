<?php 
namespace App\Libraries;

class Upload
{
    public function uploadImage($file, $uploadPath)
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            return $newName;
        }
        return null;
    }
}


?>