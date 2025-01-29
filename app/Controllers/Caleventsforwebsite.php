<?php

namespace App\Controllers;

use App\Models\CalEventForWebsite;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CalEventsForWebsite extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $model = new CalEventForWebsite();
        $data = $model->findAll();
        return $this->respond($data);
    }
}
