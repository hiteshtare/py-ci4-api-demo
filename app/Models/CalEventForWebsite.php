<?php

namespace App\Models;

use CodeIgniter\Model;

class CalEventForWebsite extends Model
{
    protected $table = 'calendar_event';
    protected $primaryKey = 'calendar_event_id';
    protected $allowedFields = ['summary'];
}
