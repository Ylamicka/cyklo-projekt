<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RaceYear as Ry;
use CodeIgniter\HTTP\ResponseInterface;

class Raceyear extends BaseController
{
    public function index()
    {
        $raceYear = new Ry();
        $dataRaceYear = $raceYear->select('year, Count(*) as pocet')->distinct()->groupBy('year')->orderBy('year', 'asc')->findAll();

        $data = [
            "raceYear" => $dataRaceYear
        ];
        return view("cyklo", $data);
    }
}
