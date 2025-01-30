<?php

namespace App\Controllers;

use App\Models\CalEventForWebsite;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CalEventsForWebsite extends BaseController
{
    public $model;

    public function __construct()
    {
        $this->model = new CalEventForWebsite();

    }
    use ResponseTrait;

    public function index()
    {
        $model = new CalEventForWebsite();
        $data = $model->findAll();
        return $this->respond($data);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function show()
    {
        $search_data = [];

        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        $error_msg = [];
        $error_msg['reason'] = '';

        if ($start_date > $end_date) {
            $error_msg['reason'] .= "Start date cannot be greater than the end date";
        }

        $search_data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        $events = $this->model->getevents($search_data);
        if (!empty($events)) {
            $response = array('success' => true, 'events' => $events, "status" => 200);
            // echo json_encode($response);
            return $this->respond($response);
        } else {
            if (!$error_msg['reason'] == '') {
                $error = $error_msg['reason'];
            } else {
                $error = "searching record not found on database";
            }
            $response = array('success' => false, "message" => $error, "status" => 204);
            // echo json_encode($response);
            return $this->respond($response);
        }
    }

}
