<?php

namespace App\Models;

use CodeIgniter\Model;

class CalEventForWebsite extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table = 'calendar_event';
    protected $primaryKey = 'calendar_event_id';
    protected $allowedFields = [
        'end_date',
        'location',
        'latitude',
        'event_type',
        'language',
        'longitude'
    ];

    function getevents($search_data)
    {
        $start_date = $search_data['start_date'];
        $end_date = $search_data['end_date'];

        $fstart = date('Y-m-d', strtotime($start_date));
        $fend = date('Y-m-d', strtotime($end_date));

        $where = '';
        if ($end_date != '') {
            $where .= " ce.dt_start >= '" . $fstart . "' AND ce.dt_end <= '" . $fend . "'";

        } else {
            $where .= "1";
        }

        $query = $this->db->query("SELECT * from calendar_event ce where $where");
        return $query->getResultArray();
    }

}
