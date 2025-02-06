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

        // To check if events is not empty
        if (!empty($events)) {
            // To group events by 'calender_id'
            $group_events = $this->group_by("calendar_id", $events);

            $curated_events = array();

            foreach ($events as $event) {
                
                // To remove redudant attributes from child grouped events
                $this->deep_unset($group_events[$event["calendar_id"]], 'calendar_id');
                $this->deep_unset($group_events[$event["calendar_id"]], 'xwr_calname');
                $this->deep_unset($group_events[$event["calendar_id"]], 'xwr_timezone');

                // To create parent child array based on key 'calendar_id'
                $curated_events[] = array(
                    'calendar_id' => $event["calendar_id"],
                    'xwr_calname' => $event["xwr_calname"],
                    'xwr_timezone' => $event["xwr_timezone"],
                    'event_date' => $group_events[$event["calendar_id"]]
                );
            }

            // To sort curated events using array unique
            $curated_events = array_unique($curated_events, SORT_REGULAR);

            $response = array('success' => true, 'events' => $curated_events, "status" => 200);
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

    /**
     * Unsets array keys and object properties of the given name.
     *
     * @param array|object $data  An iterable object or array to modify.
     * @param string       $field The name of the key or property to remove.
     */
    function deep_unset(array|object &$data, string $field)
    {

        if (is_array($data)) {
            unset($data[$field]);
        } elseif (is_object($data)) {
            unset($data->{$field});
        }

        foreach ($data as &$value) {
            if (is_array($value) || is_object($value)) {
                $this->deep_unset($value, $field);
            }
        }
    }
}
