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
        $time_zone = $this->request->getVar('time_zone');

        $error_msg = [];
        $error_msg['reason'] = '';

        if ($start_date > $end_date) {
            $error_msg['reason'] .= "Start date cannot be greater than the end date";
        }

        $search_data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'time_zone' => $time_zone
        ];

        $events = $this->model->getevents($search_data);
        if (!empty($events)) {

            $group_events = $this->group_by("calendar_id", $events);

            $response = array('success' => true, 'events' => $group_events, "status" => 200);
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


    /**
     * Function that groups an array of associative arrays by some key.
     * 
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}
