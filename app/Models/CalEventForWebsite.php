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
        $time_zone = $search_data['time_zone'];

        $fstart = date('Y-m-d', strtotime($start_date));
        $fend = date('Y-m-d', strtotime($end_date));

        $where = 'ce.status =1 AND ';
        if ($end_date != '') {
            $where .= " ce.dt_start >= '" . $fstart . "' AND ce.dt_end <= '" . $fend . "'";

        }
        if ($time_zone != '') {
            $where .= " AND cm.xwr_timezone='" . $time_zone . "' ";
        } else {
            $where .= "1";
        }

        $query = $this->db->query("SELECT cm.calendar_id,cm.xwr_calname ,cm.xwr_timezone,
ce.calendar_event_id,ce.calendar_id,ce.summary,ce.description,ce.organiser,ce.
dt_start,ce.dt_end,ce.city,ce.location from calendar_event ce
inner join calendar_master cm
on ce.calendar_id = cm.calendar_id 
 where $where");

        return $query->getResultArray();
    }

}
