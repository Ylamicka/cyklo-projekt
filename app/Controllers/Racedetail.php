<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceStages as Rs;
use App\Models\RaceYear as Ry;
use App\Models\RaceUciTour as Rut;
use Config\MainConfig as Mc;

use App\Libraries\Upload;
 
 
 
class Racedetail extends BaseController
{
    public function index($rok)
    {
        $raceYear = new Ry();
        $config = new Mc();
       
        $uciTourModel = new Rut();
        $uciTours = $uciTourModel->findAll();
 
        $dataDetail = $raceYear->select('cyklo_race_year.id AS race_year_id, cyklo_uci_tour_type.name AS uci_tour_name, cyklo_race_year.uci_tour, cyklo_race_year.year, cyklo_race_year.real_name, cyklo_race_year.start_date, cyklo_race_year.end_date, cyklo_stage.vertical_meters, cyklo_stage.distance, cyklo_stage.arrival, cyklo_race_year.country, cyklo_race_year.logo')
            ->join('cyklo_stage', 'cyklo_stage.id_race_year = cyklo_race_year.id', 'left')
            ->join("cyklo_uci_tour_type", "cyklo_uci_tour_type.id = cyklo_race_year.uci_tour", 'left')
            ->where('cyklo_race_year.year', $rok)
            ->paginate($config->PagerNumber);
 
        $data = [
            "detail" => $dataDetail,
            "vybranyRok" => $rok,
            "pager" => $raceYear->pager,
            "uciTours" => $uciTours
        ];
 
        return view("racedetail", $data);
    }
 
    // 1. Přidání nového ročníku
    public function add()
    {
        $raceYear = new Ry();
        $raceStage = new Rs();
        $uploadLib = new Upload();
       
        $yearData = [
            'year'       => $this->request->getPost('year'),
            'real_name'  => $this->request->getPost('real_name') ?: 'Obecný název závodu',
            'uci_tour'   => $this->request->getPost('uci_tour') ?: 0, 
            'country'    => $this->request->getPost('country') ?: 'cz',
            'start_date' => $this->request->getPost('start_date') ?: date('Y-m-d'),
            'end_date'   => $this->request->getPost('end_date') ?: date('Y-m-d'),
        ];
 
        $uploadedLogo = $uploadLib->uploadImage('logo', 'Images');
        if ($uploadedLogo) {
            $yearData['logo'] = $uploadedLogo;
        }
 
        $raceYear->insert($yearData);
        $newRaceYearId = $raceYear->getInsertID();
 
        $stageData = [
            'id_race_year'    => $newRaceYearId,
            'distance'        => $this->request->getPost('distance') ?: 0,
            'vertical_meters' => $this->request->getPost('vertical_meters') ?: 0,
        ];
       
        $raceStage->insert($stageData);
 
        $page = $this->request->getPost('page') ?: 1;
        return redirect()->to('zavody/rok/' . $yearData['year'] . '?page=' . $page)->with('success', 'Ročník byl úspěšně přidán.');
    }
 
    // 2. Editace ročníku
    public function edit($id)
    {
        $raceYear = new Ry();
        $raceStage = new Rs();
        $uploadLib = new Upload();
       
        $yearData = [
            'year'       => $this->request->getPost('year'),
            'real_name'  => $this->request->getPost('real_name'),
            'country'    => $this->request->getPost('country'),
            'uci_tour'   => $this->request->getPost('uci_tour') ?: 0,
            'start_date' => $this->request->getPost('start_date') ?: date('Y-m-d'),
            'end_date'   => $this->request->getPost('end_date') ?: date('Y-m-d'),
        ];
 
        $uploadedLogo = $uploadLib->uploadImage('logo', 'Images');
        if ($uploadedLogo) {
            $yearData['logo'] = $uploadedLogo;
        }
 
        $raceYear->update($id, $yearData);
 
        $stage = $raceStage->where('id_race_year', $id)->first();
        $stageData = [
            'distance'        => $this->request->getPost('distance') ?: 0,
            'vertical_meters' => $this->request->getPost('vertical_meters') ?: 0,
        ];
 
        if ($stage) {
            $raceStage->update($stage->id, $stageData);
        } else {
            $stageData['id_race_year'] = $id;
            $raceStage->insert($stageData);
        }
 
        $page = $this->request->getPost('page') ?: 1;
        return redirect()->to('zavody/rok/' . $yearData['year'] . '?page=' . $page)->with('success', 'Ročník byl úspěšně upraven.');
    }
 
    // 3. Smazání ročníku
public function delete($id)
{
    $raceYear = new Ry();
    $record = $raceYear->find($id);
   
    if ($record) {
        $rok = $record->year;
        $raceYear->delete($id);
    
       
        $page = $this->request->getPost('page') ?: 1;

        return redirect()->to('zavody/rok/' . $rok . '?page=' . $page)->with('success', 'Ročník byl úspěšně smazán.');
    }
   
    return redirect()->back();
}
}