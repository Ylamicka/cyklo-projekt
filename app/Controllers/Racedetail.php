<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceStages as Rs;
use App\Models\RaceYear as Ry;
use Config\MainConfig as Mc;

use App\Libraries\Upload;
 
 
 
class Racedetail extends BaseController
{
    public function index($rok)
    {
        $raceYear = new Ry();
 
        $config = new Mc();
 
        
        $dataDetail = $raceYear->select('cyklo_race_year.id AS race_year_id, cyklo_race_year.uci_tour, cyklo_race_year.year, cyklo_race_year.real_name, cyklo_race_year.start_date, cyklo_race_year.end_date, cyklo_stage.vertical_meters, cyklo_stage.distance, cyklo_stage.arrival,cyklo_race_year.country, cyklo_race_year.logo')
            ->join('cyklo_stage', 'cyklo_stage.id_race_year = cyklo_race_year.id', 'left')
            ->where('cyklo_race_year.year', $rok)
            ->paginate($config->PagerNumber);
 
        $data = [
            "detail" => $dataDetail,
            "vybranyRok" => $rok,
            "pager" => $raceYear->pager
        ];
 
        return view("racedetail", $data);
    }
 
   
 
    // 1. Přidání nového ročníku
    public function add()
    {
        $raceYear = new Ry();
       
        $data = [
            'year'      => $this->request->getPost('year'),
            'real_name' => $this->request->getPost('real_name') ?: 'Obecný název závodu',
            'uci_tour'  => $this->request->getPost('uci_tour'),
            'country'   => $this->request->getPost('country') ?: 'cz'
        ];
 
        $upload = new Upload();
        // Zpracování loga
        $img = $this->request->getFile('logo');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/Images', $newName);
            $data['logo'] = $newName;
        }
 
        $data['uci_tour'] = $data['uci_tour'] ?? '';
        $raceYear->insert($data);
        return redirect()->to('zavody/rok/' . $data['year'])->with('success', 'Ročník byl úspěšně přidán.');
    }
 
    // 2. Editace ročníku
    public function edit($id)
    {
        $raceYear = new Ry();
       
        $data = [
            'year'      => $this->request->getPost('year'),
            'real_name' => $this->request->getPost('real_name'),
            'uci_tour'  => $this->request->getPost('uci_tour'),
        ];
 
       
        $img = $this->request->getFile('logo');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/Images', $newName);
            $data['logo'] = $newName;
        }
 
        $raceYear->update($id, $data);
        return redirect()->to('zavody/rok/' . $data['year'])->with('success', 'Ročník byl úspěšně upraven.');
    }
 
    // 3. Smazání ročníku
    public function delete($id)
    {
        $raceYear = new Ry();
        $record = $raceYear->find($id);
       
        if ($record) {
            $raceYear->delete($id);
        
            return redirect()->back()->with('success', 'Ročník byl smazán.');
        }
       
        return redirect()->back();
    }
}