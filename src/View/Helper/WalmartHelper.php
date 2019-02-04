<?php

namespace App\View\Helper;

use Cake\View\Helper;

class WalmartHelper extends Helper
{
    public function codeFormat($company_keyword = null, $internal_code = null)
    {
        $code = '';

        if($company_keyword != null && $internal_code != null){
            
            switch ($company_keyword) {
                case 'lider':
                    $code_wt_digit = '040000'.$internal_code;
                    $digit = $this->ean13_checksum($code_wt_digit);

                    $code = strval($code_wt_digit.$digit);
                    break;
                
                default:
                    $code_wt_digit = '040000'.$internal_code;
                    $digit = $this->ean13_checksum($code_wt_digit);

                    $code = strval($code_wt_digit.$digit);
                    break;
            }
        }

        return $code;
    }

    function ean13_checksum ($message) {
        $checksum = 0;
        foreach (str_split(strrev($message)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        return ((10 - ($checksum % 10)) % 10);
    }
}