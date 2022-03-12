<?php

namespace App\Http\Controllers;

use App\Models\Area;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
// 1 caso: Dias com intervalos
//seg-ter 06:00 ás 22:00
//Qui-Sex 06:00 ás 22:00

//1 - Seg
//2 - Ter
//4 - Qui
//5 - Sex

// Segundo caso: Dias sequenciais
// Seg-Sex 07:00 ás 23:00

    public function getReservations() {
        $array = ['error' => ''];
        $daysHelper = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];

        $areas = Area::where('allowed', 1)->get();

        foreach($areas as $area) {
            $dayList = explode(',', $area['days']);

            $dayGroups = [];

            // Adicionando o primeiro dia
            $lastDay = intval(current($dayList));
            $dayGroups[] = $daysHelper[$lastDay];
            array_shift($dayList);


            // Adicionando o ultimo dia
            $dayGroups[] = $daysHelper[end($dayList)];

            echo "Área: " .$area['title']. " \n";
            print_r($dayGroups);
            echo "\n -------------------";
        }

        $array['list'] = $areas;
        return $array;
    }
}
