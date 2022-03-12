<?php

namespace App\Http\Controllers;

use App\Models\Area;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function getReservations() {
        $array = ['error' => ''];
        $areas = Area::where('allowed', 1)->get();
        $array['list'] = $areas;
        return $array;
    }
}
