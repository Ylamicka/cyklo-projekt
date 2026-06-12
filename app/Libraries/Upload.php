<?php
 
namespace App\Libraries;
 
use CodeIgniter\HTTP\IncomingRequest;
 
class Upload
{
public function uploadImage(string $fieldName, string $targetFolder = 'Images'): ?string
    {
       
        $request = \Config\Services::request();
        $img = $request->getFile($fieldName);
 
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
           
           
            $img->move(FCPATH . $targetFolder, $newName);
           
            return $newName;
        }
 
        return null;
    }
}