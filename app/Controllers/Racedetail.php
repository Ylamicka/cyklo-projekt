<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceStages as Rs;
use App\Models\RaceYear as Ry;

class Racedetail extends BaseController
{
    public function index($rok)
    {
        $raceYear = new Ry();

        $dataDetail = $raceYear->select('cyklo_race_year.year, cyklo_race_year.real_name, cyklo_race_year.start_date, cyklo_race_year.    end_date, cyklo_stage.vertical_meters, cyklo_stage.distance, cyklo_stage.arrival,cyklo_race_year.country')->join('cyklo_stage', 'cyklo_stage.id_race_year = cyklo_race_year.id')->where('cyklo_race_year.year', $rok)->paginate(9);

        $data = [
            "detail" => $dataDetail,
            "vybranyRok" => $rok,
            "pager" => $raceYear->pager
        ];

        return view("racedetail", $data);
    }
}
