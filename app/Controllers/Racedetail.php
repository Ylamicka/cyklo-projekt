<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Racedetail extends BaseController
{
    public function index()
    {
        return view("racedetail");
    }
}
