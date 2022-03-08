<?php

namespace App\Http\Controllers;
use App\Models\FoundAndLost;
use Illuminate\Http\Request;

class FoundAndLostController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        $lost = FoundAndLost::where('status', 'LOST')
        ->orderBy('datecreated', 'DESC')
        ->orderBy('id', 'DESC')
        ->get();

        $array['lost'] = $lost;

        return $array;
    }
}
