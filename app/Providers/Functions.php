<?php

namespace App\Providers;

class Functions{

    public static function stringToDate($dateExcel){

        if($dateExcel === '' || $dateExcel === NULL)
            return NULL;

        if(gettype($dateExcel) === 'integer'){
            $date = date('Y-m-d 00:00', intval((intval($dateExcel) - 25569)*24*60*60 ));
            return $date;
        }elseif (strpos($dateExcel, '/') !== false){
            $array = explode('/', $dateExcel);
            $finalDateExcel = $array[2].'-'.$array[1].'-'.$array[0].' 00:00';

            return new \DateTime($finalDateExcel);
        }else{
            return NULL;
        }

    }
}