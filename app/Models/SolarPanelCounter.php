<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarPanelCounter extends Model
{
    protected $table = 'solar_panel_counters';
    protected $guarded = [];
    protected $fillable = [
        'date', 
        'counter_reading'
    ];
}
