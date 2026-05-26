<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceStages as Rs;

class Racedetail extends BaseController
{
    public function index()
    {
        $racestage = new Rs();
        $dataRaceStage = $racestage->select();
        
        return view("racedetail");
    }
}
