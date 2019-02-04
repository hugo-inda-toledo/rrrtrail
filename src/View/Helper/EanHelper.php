<?php

namespace App\View\Helper;

use Cake\View\Helper;

class EanHelper extends Helper
{
    public function format($ean_code = null, $company_keyword = null)
    {   
        if(strlen($ean_code) > 13){
            return false;
        }

        if(strlen($ean_code) == 13){
            return $ean_code;
        }
        else{
            $difference = 13 - strlen($ean_code);

            $before = '';
            $code = '';
            for($x=0; $x < $difference; $x++){
                $before .= '0';
            }

            $code = $before.$ean_code;

            //$digit = $this->ean13_checksum($code);
            return $code;
        }
    }

    function ean13_checksum($message) {

        $checksum = 0;
            foreach (str_split(strrev($message)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        return ((10 - ($checksum % 10)) % 10);
    }
}