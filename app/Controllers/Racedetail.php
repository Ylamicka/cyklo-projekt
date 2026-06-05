<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceStages as Rs;
use App\Models\RaceYear as Ry;
use Config\MainConfig as Mc;



class Racedetail extends BaseController
{
    public function index($rok)
    {
        $raceYear = new Ry();

        $config = new Mc();

        $dataDetail = $raceYear->select('cyklo_race_year.year, cyklo_race_year.real_name, cyklo_race_year.start_date, cyklo_race_year.end_date, cyklo_stage.vertical_meters, cyklo_stage.distance, cyklo_stage.arrival,cyklo_race_year.country')->join('cyklo_stage', 'cyklo_stage.id_race_year = cyklo_race_year.id')->where('cyklo_race_year.year', $rok)->paginate($config->PagerNumber);

        $data = [
            "detail" => $dataDetail,
            "vybranyRok" => $rok,
            "pager" => $raceYear->pager
        ];

        return view("racedetail", $data);
    }
    public function add($zobrazenyRok)
    {
        $raceYear = new Ry();

        $insertData = [
            'year'      => $this->request->getPost('year'),
            'real_name' => $this->request->getPost('real_name'),
            'uci_tour'  => $this->request->getPost('uci_tour'),
            'country'   => 'cz' 
        ];

       
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'Images', $newName);
            $insertData['logo'] = $newName;
        }

        $raceYear->insert($insertData);
        return redirect()->to(base_url("racedetail/" . $zobrazenyRok));
    }

    
    public function update($zobrazenyRok)
    {
        $raceYear = new Ry();
        $id = $this->request->getPost('id');

        $updateData = [
            'year'      => $this->request->getPost('year'),
            'real_name' => $this->request->getPost('real_name'),
            'uci_tour'  => $this->request->getPost('uci_tour')
        ];

        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'Images', $newName);
            $updateData['logo'] = $newName;
        }

        $raceYear->update($id, $updateData);
        return redirect()->to(base_url("racedetail/" . $zobrazenyRok));
    }

    public function delete($id, $zobrazenyRok)
    {
        $raceYear = new Ry();
        $raceYear->delete($id);
        
        return redirect()->to(base_url("racedetail/" . $zobrazenyRok));
    }
}

