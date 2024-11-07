<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateFormatter
{
    public static function today($format = 'Y-m-d H:i:s')
    {
        return Carbon::now('America/La_Paz')->format($format);
    }
}
