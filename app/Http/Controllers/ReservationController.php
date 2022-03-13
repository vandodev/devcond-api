<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaDisabledDay;

use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function getReservations() {
        $array = ['error' => '', 'list' => []];
        $daysHelper = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];

        $areas = Area::where('allowed', 1)->get();

        foreach($areas as $area) {
            $dayList = explode(',', $area['days']);

            $dayGroups = [];

            // Adicionando o primeiro dia
            $lastDay = intval(current($dayList));
            $dayGroups[] = $daysHelper[$lastDay];
            array_shift($dayList);

            // adicionando dias relevantes (intervalos)
            foreach($dayList as $day) {
                if(intval($day) != $lastDay+1) {
                    $dayGroups[] = $daysHelper[$lastDay];
                    $dayGroups[] = $daysHelper[$day];
                }

                $lastDay = intval($day);
            }

            // Adicionando o ultimo dia
            $dayGroups[] = $daysHelper[end($dayList)];

            // Juntando as datas (Dia1 - Dia2)
            $dates = '';
            $close = 0;
            foreach($dayGroups as $group) {
                if($close === 0) {
                    $dates .= $group;
                } else {
                    $dates .= '-'.$group.',';
                }
                $close = 1 - $close;
            }

            $dates = explode(',', $dates);
            array_pop($dates);

             // adicionando o TIME
            $start = date('H:i', strtotime($area['start_time']));
            $end = date('H:i', strtotime($area['end_time']));

            foreach($dates as $dKey => $dValue) {
                $dates[$dKey] .= ' '.$start. ' às '.$end;
            }


           $array['list'][] = [
                'id' => $area['id'],
                'cover' => asset('storage/'.$area['cover']),
                'title' => $area['title'],
                'dates' => $dates
            ];
        }

      return $array;
    }

    public function getDisabledDates($id) {
        $array = ['error' => '', 'list' => []];

        $area = Area::find($id);
        if($area) {
            // Dias disabled padrão
            $disabledDays = AreaDisabledDay::where('id_area', $id )->get();
            foreach($disabledDays as $disabledDay) {
                 $array['list'][] = $disabledDay['day'];
            }
            // Dias disabled através do allowed
            $allowedDays = explode(',', $area['days']);

            $offDays = [];
            for($q=0;$q<7;$q++) {
                if(!in_array($q, $allowedDays)) {
                    $offDays[] = $q;
             }
        }
        print_r($allowedDays);
        print_r($offDays);
        }else{
            $array['error'] = 'Area inexistente!';
            return $array;
        }


      return $array;
    }

}
