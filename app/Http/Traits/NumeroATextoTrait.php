<?php

namespace App\Http\Traits;

trait NumeroATextoTrait
{
    function NumLet($Cant) {
        $Cad1=0;
        $Resto=0;
        $Cad1 = number_format($Cant,2);
        $Resto = substr($Cad1,strlen($Cad1)-2,2);
        $Cad1 = substr($Cad1,0,strlen($Cad1)-3);
        if (strlen($Cad1) < 3) {
            $Cad2 = $this->Del1al99($Cad1);
            $NumLet = $Cad2;
        }


        if (strlen($Cad1) == 3) {
            $Cad3 ="";
            if ($Cad1==100){
                $Cad3 = "CIEN";
            }else{
                $Cad3=$this->Cientos(substr($Cad1,0,1));
                $Cad3=$Cad3." ".$this->Del1al99(substr($Cad1,1,2));
            }
            $NumLet = $Cad3;
        }


        if (strlen($Cad1) == 5 or strlen($Cad1) == 6){
            if (strlen($Cad1)==5) {
                $Cad5=$this->Miles(substr($Cad1,0,1));
                $Cad6=substr($Cad1,2,3);
            }
            if (strlen($Cad1)==6) {
                $Cad5=$this->Miles(substr($Cad1,0,2));
                $Cad6=substr($Cad1,3,3);
            }
            if (strlen($Cad6) < 3) {
                $Cad7 = $this->Del1al99($Cad6);
            }
            if (strlen($Cad6) == 3){
                $Cad7 ="";
                if ($Cad6==100) {
                    $Cad7 = "CIEN";
                }
                else {
                    $Cad7=$this->Cientos(substr($Cad6,0,1));
                    $Cad7=$Cad7." ".$this->Del1al99(substr($Cad6,1,2));
                }
            }
            $NumLet = $Cad5." ".$Cad7;
        }


        if (strlen($Cad1) == 7) {
            $CadA = substr($Cad1,0,3);
            $Cad3 ="";
            if ($CadA==100) {
                $Cad3 = "CIEN";
            }
            else {
                $Cad3=$this->Cientos(substr($CadA,0,1));
                $Cad3=$Cad3." ".$this->Del1al99(substr($CadA,1,2));
            }
            $Cad3 = $Cad3." MIL ";
            $CadB = substr($Cad1,4,3);

            if ($CadB==100){
                $Cad4 = "CIEN";
            }else{
                $Cad4=$this->Cientos(substr($CadB,0,1));
                $Cad4=$Cad4." ".$this->Del1al99(substr($CadB,1,2));
            }
            $NumLet = $Cad3.$Cad4;
        }

        $NumLet = $NumLet." PESOS ".$Resto."/100";
        
        return $NumLet;
    }

    function Del1al99($Valor){
        $Del1al99 = "";
        If ($Valor == 1){$Del1al99 = "UN";}
        If ($Valor == 2) {$Del1al99 = "DOS";}
        If ($Valor == 3) {$Del1al99 = "TRES";}
        If ($Valor == 4) {$Del1al99 = "CUATRO";}
        If ($Valor == 5) {$Del1al99 = "CINCO";}
        If ($Valor == 6) {$Del1al99 = "SEIS";}
        If ($Valor == 7) {$Del1al99 = "SIETE";}
        If ($Valor == 8) {$Del1al99 = "OCHO";}
        If ($Valor == 9) {$Del1al99 = "NUEVE";}
        If ($Valor == 10) {$Del1al99 = "DIEZ";}
        If ($Valor == 11) {$Del1al99 = "ONCE";}
        If ($Valor == 12) {$Del1al99 = "DOCE";}
        If ($Valor == 13) {$Del1al99 = "TRECE";}
        If ($Valor == 14) {$Del1al99 = "CATORCE";}
        If ($Valor == 15) {$Del1al99 = "QUINCE";}
        If ($Valor == 16) {$Del1al99 = "DIEZ Y SEIS";}
        If ($Valor == 17) {$Del1al99 = "DIEZ Y SIETE";}
        If ($Valor == 18) {$Del1al99 = "DIEZ Y OCHO";}
        If ($Valor == 19) {$Del1al99 = "DIEZ Y NUEVE";}
        If ($Valor == 20) {$Del1al99 = "VEINTE";}
        If ($Valor == 21) {$Del1al99 = "VEINTIUNO";}
        If ($Valor == 22) {$Del1al99 = "VEINTIDOS";}
        If ($Valor == 23) {$Del1al99 = "VEINTITRES";}
        If ($Valor == 24) {$Del1al99 = "VEINTICUATRO";}
        If ($Valor == 25) {$Del1al99 = "VEINTICINCO";}
        If ($Valor == 26) {$Del1al99 = "VEINTISEIS";}
        If ($Valor == 27) {$Del1al99 = "VEINTISIETE";}
        If ($Valor == 28) {$Del1al99 = "VEINTIOCHO";}
        If ($Valor == 29) {$Del1al99 = "VEINTINUEVE";}
        If ($Valor == 30) {$Del1al99 = "TREINTA";}
        If ($Valor == 31) {$Del1al99 = "TREINTA Y UNO";}
        If ($Valor == 32) {$Del1al99 = "TREINTA Y DOS";}
        If ($Valor == 33) {$Del1al99 = "TREINTA Y TRES";}
        If ($Valor == 34) {$Del1al99 = "TREINTA Y CUATRO";}
        If ($Valor == 35) {$Del1al99 = "TREINTA Y CINCO";}
        If ($Valor == 36) {$Del1al99 = "TREINTA Y SEIS";}
        If ($Valor == 37) {$Del1al99 = "TREINTA Y SIETE";}
        If ($Valor == 38) {$Del1al99 = "TREINTA Y OCHO";}
        If ($Valor == 39) {$Del1al99 = "TREINTA Y NUEVE";}
        If ($Valor == 40) {$Del1al99 = "CUARENTA";}
        If ($Valor == 41) {$Del1al99 = "CUARENTA Y UNO";}
        If ($Valor == 42) {$Del1al99 = "CUARENTA Y DOS";}
        If ($Valor == 43) {$Del1al99 = "CUARENTA Y TRES";}
        If ($Valor == 44) {$Del1al99 = "CUARENTA Y CUATRO";}
        If ($Valor == 45) {$Del1al99 = "CUARENTA Y CINCO";}
        If ($Valor == 46) {$Del1al99 = "CUARENTA Y SEIS";}
        If ($Valor == 47) {$Del1al99 = "CUARENTA Y SIETE";}
        If ($Valor == 48) {$Del1al99 = "CUARENTA Y OCHO";}
        If ($Valor == 49) {$Del1al99 = "CUARENTA Y NUEVE";}
        If ($Valor == 50) {$Del1al99 = "CINCUENTA";}
        If ($Valor == 51) {$Del1al99 = "CINCUENTA Y UNO";}
        If ($Valor == 52) {$Del1al99 = "CINCUENTA Y DOS";}
        If ($Valor == 53) {$Del1al99 = "CINCUENTA Y TRES";}
        If ($Valor == 54) {$Del1al99 = "CINCUENTA Y CUATRO";}
        If ($Valor == 55) {$Del1al99 = "CINCUENTA Y CINCO";}
        If ($Valor == 56) {$Del1al99 = "CINCUENTA Y SEIS";}
        If ($Valor == 57) {$Del1al99 = "CINCUENTA Y SIETE";}
        If ($Valor == 58) {$Del1al99 = "CINCUENTA Y OCHO";}
        If ($Valor == 59) {$Del1al99 = "CINCUENTA Y NUEVE";}
        If ($Valor == 60) {$Del1al99 = "SESENTA";}
        If ($Valor == 61) {$Del1al99 = "SESENTA Y UNO";}
        If ($Valor == 62) {$Del1al99 = "SESENTA Y DOS";}
        If ($Valor == 63) {$Del1al99 = "SESENTA Y TRES";}
        If ($Valor == 64) {$Del1al99 = "SESENTA Y CUATRO";}
        If ($Valor == 65) {$Del1al99 = "SESENTA Y CINCO";}
        If ($Valor == 66) {$Del1al99 = "SESENTA Y SEIS";}
        If ($Valor == 67) {$Del1al99 = "SESENTA Y SIETE";}
        If ($Valor == 68) {$Del1al99 = "SESENTA Y OCHO";}
        If ($Valor == 69) {$Del1al99 = "SESENTA Y NUEVE";}
        If ($Valor == 70) {$Del1al99 = "SETENTA";}
        If ($Valor == 71) {$Del1al99 = "SETENTA Y UNO";}
        If ($Valor == 72) {$Del1al99 = "SETENTA Y DOS";}
        If ($Valor == 73) {$Del1al99 = "SETENTA Y TRES";}
        If ($Valor == 74) {$Del1al99 = "SETENTA Y CUATRO";}
        If ($Valor == 75) {$Del1al99 = "SETENTA Y CINCO";}
        If ($Valor == 76) {$Del1al99 = "SETENTA Y SEIS";}
        If ($Valor == 77) {$Del1al99 = "SETENTA Y SIETE";}
        If ($Valor == 78) {$Del1al99 = "SETENTA Y OCHO";}
        If ($Valor == 79) {$Del1al99 = "SETENTA Y NUEVE";}
        If ($Valor == 80) {$Del1al99 = "OCHENTA";}
        If ($Valor == 81) {$Del1al99 = "OCHENTA Y UNO";}
        If ($Valor == 82) {$Del1al99 = "OCHENTA Y DOS";}
        If ($Valor == 83) {$Del1al99 = "OCHENTA Y TRES";}
        If ($Valor == 84) {$Del1al99 = "OCHENTA Y CUATRO";}
        If ($Valor == 85) {$Del1al99 = "OCHENTA Y CINCO";}
        If ($Valor == 86) {$Del1al99 = "OCHENTA Y SEIS";}
        If ($Valor == 87) {$Del1al99 = "OCHENTA Y SIETE";}
        If ($Valor == 88) {$Del1al99 = "OCHENTA Y OCHO";}
        If ($Valor == 89) {$Del1al99 = "OCHENTA Y NUEVE";}
        If ($Valor == 90) {$Del1al99 = "NOVENTA";}
        If ($Valor == 91) {$Del1al99 = "NOVENTA Y UNO";}
        If ($Valor == 92) {$Del1al99 = "NOVENTA Y DOS";}
        If ($Valor == 93) {$Del1al99 = "NOVENTA Y TRES";}
        If ($Valor == 94) {$Del1al99 = "NOVENTA Y CUATRO";}
        If ($Valor == 95) {$Del1al99 = "NOVENTA Y CINCO";}
        If ($Valor == 96) {$Del1al99 = "NOVENTA Y SEIS";}
        If ($Valor == 97) {$Del1al99 = "NOVENTA Y SIETE";}
        If ($Valor == 98) {$Del1al99 = "NOVENTA Y OCHO";}
        If ($Valor == 99) {$Del1al99 = "NOVENTA Y NUEVE";}
        return $Del1al99;
    }

    function Cientos($Valor){
        $Cientos = "";
        If ($Valor == 1) {$Cientos = "CIENTO";}
        If ($Valor == 2) {$Cientos = "DOSCIENTOS";}
        If ($Valor == 3) {$Cientos = "TRESCIENTOS";}
        If ($Valor == 4) {$Cientos = "CUATROCIENTOS";}
        If ($Valor == 5) {$Cientos = "QUINIENTOS";}
        If ($Valor == 6) {$Cientos = "SEISCIENTOS";}
        If ($Valor == 7) {$Cientos = "SETECIENTOS";}
        If ($Valor == 8) {$Cientos = "OCHOCIENTOS";}
        If ($Valor == 9) {$Cientos = "NOVECIENTOS";}
        return $Cientos;
    }

    function Miles($Valor){
        $Miles = "";
        If ($Valor == 1){$Miles = "MIL";}
        If ($Valor == 2) {$Miles = "DOS MIL";}
        If ($Valor == 3) {$Miles = "TRES MIL";}
        If ($Valor == 4) {$Miles = "CUATRO MIL";}
        If ($Valor == 5) {$Miles = "CINCO MIL";}
        If ($Valor == 6) {$Miles = "SEIS MIL";}
        If ($Valor == 7) {$Miles = "SIETE MIL";}
        If ($Valor == 8) {$Miles = "OCHO MIL";}
        If ($Valor == 9) {$Miles = "NUEVE MIL";}
        If ($Valor == 10) {$Miles = "DIEZ MIL";}
        If ($Valor == 11) {$Miles = "ONCE MIL";}
        If ($Valor == 12) {$Miles = "DOCE MIL";}
        If ($Valor == 13) {$Miles = "TRECE MIL";}
        If ($Valor == 14) {$Miles = "CATORCE MIL";}
        If ($Valor == 15) {$Miles = "QUINCE MIL";}
        If ($Valor == 16) {$Miles = "DIEZ Y SEIS MIL";}
        If ($Valor == 17) {$Miles = "DIEZ Y SIETE MIL";}
        If ($Valor == 18) {$Miles = "DIEZ Y OCHO MIL";}
        If ($Valor == 19) {$Miles = "DIEZ Y NUEVE MIL";}
        If ($Valor == 20) {$Miles = "VEINTE MIL";}
        If ($Valor == 21) {$Miles = "VEINTIUNO MIL";}
        If ($Valor == 22) {$Miles = "VEINTIDOS MIL";}
        If ($Valor == 23) {$Miles = "VEINTITRES MIL";}
        If ($Valor == 24) {$Miles = "VEINTICUATRO MIL";}
        If ($Valor == 25) {$Miles = "VEINTICINCO MIL";}
        If ($Valor == 26) {$Miles = "VEINTISEIS MIL";}
        If ($Valor == 27) {$Miles = "VEINTISIETE MIL";}
        If ($Valor == 28) {$Miles = "VEINTIOCHO MIL";}
        If ($Valor == 29) {$Miles = "VEINTINUEVE MIL";}
        If ($Valor == 30) {$Miles = "TREINTA MIL";}
        If ($Valor == 31) {$Miles = "TREINTA Y UNO MIL";}
        If ($Valor == 32) {$Miles = "TREINTA Y DOS MIL";}
        If ($Valor == 33) {$Miles = "TREINTA Y TRES MIL";}
        If ($Valor == 34) {$Miles = "TREINTA Y CUATRO MIL";}
        If ($Valor == 35) {$Miles = "TREINTA Y CINCO MIL";}
        If ($Valor == 36) {$Miles = "TREINTA Y SEIS MIL";}
        If ($Valor == 37) {$Miles = "TREINTA Y SIETE MIL";}
        If ($Valor == 38) {$Miles = "TREINTA Y OCHO MIL";}
        If ($Valor == 39) {$Miles = "TREINTA Y NUEVE MIL";}
        If ($Valor == 40) {$Miles = "CUARENTA MIL";}
        If ($Valor == 41) {$Miles = "CUARENTA Y UNO MIL";}
        If ($Valor == 42) {$Miles = "CUARENTA Y DOS MIL";}
        If ($Valor == 43) {$Miles = "CUARENTA Y TRES MIL";}
        If ($Valor == 44) {$Miles = "CUARENTA Y CUATRO MIL";}
        If ($Valor == 45) {$Miles = "CUARENTA Y CINCO MIL";}
        If ($Valor == 46) {$Miles = "CUARENTA Y SEIS MIL";}
        If ($Valor == 47) {$Miles = "CUARENTA Y SIETE MIL";}
        If ($Valor == 48) {$Miles = "CUARENTA Y OCHO MIL";}
        If ($Valor == 49) {$Miles = "CUARENTA Y NUEVE MIL";}
        If ($Valor == 50) {$Miles = "CINCUENTA MIL";}
        If ($Valor == 51) {$Miles = "CINCUENTA Y UNO MIL";}
        If ($Valor == 52) {$Miles = "CINCUENTA Y DOS MIL";}
        If ($Valor == 53) {$Miles = "CINCUENTA Y TRES MIL";}
        If ($Valor == 54) {$Miles = "CINCUENTA Y CUATRO MIL";}
        If ($Valor == 55) {$Miles = "CINCUENTA Y CINCO MIL";}
        If ($Valor == 56) {$Miles = "CINCUENTA Y SEIS MIL";}
        If ($Valor == 57) {$Miles = "CINCUENTA Y SIETE MIL";}
        If ($Valor == 58) {$Miles = "CINCUENTA Y OCHO MIL";}
        If ($Valor == 59) {$Miles = "CINCUENTA Y NUEVE MIL";}
        If ($Valor == 60) {$Miles = "SESENTA MIL";}
        If ($Valor == 61) {$Miles = "SESENTA Y UNO MIL";}
        If ($Valor == 62) {$Miles = "SESENTA Y DOS MIL";}
        If ($Valor == 63) {$Miles = "SESENTA Y TRES MIL";}
        If ($Valor == 64) {$Miles = "SESENTA Y CUATRO MIL";}
        If ($Valor == 65) {$Miles = "SESENTA Y CINCO MIL";}
        If ($Valor == 66) {$Miles = "SESENTA Y SEIS MIL";}
        If ($Valor == 67) {$Miles = "SESENTA Y SIETE MIL";}
        If ($Valor == 68) {$Miles = "SESENTA Y OCHO MIL";}
        If ($Valor == 69) {$Miles = "SESENTA Y NUEVE MIL";}
        If ($Valor == 70) {$Miles = "SETENTA MIL";}
        If ($Valor == 71) {$Miles = "SETENTA Y UNO MIL";}
        If ($Valor == 72) {$Miles = "SETENTA Y DOS MIL";}
        If ($Valor == 73) {$Miles = "SETENTA Y TRES MIL";}
        If ($Valor == 74) {$Miles = "SETENTA Y CUATRO MIL";}
        If ($Valor == 75) {$Miles = "SETENTA Y CINCO MIL";}
        If ($Valor == 76) {$Miles = "SETENTA Y SEIS MIL";}
        If ($Valor == 77) {$Miles = "SETENTA Y SIETE MIL";}
        If ($Valor == 78) {$Miles = "SETENTA Y OCHO MIL";}
        If ($Valor == 79) {$Miles = "SETENTA Y NUEVE MIL";}
        If ($Valor == 80) {$Miles = "OCHENTA MIL";}
        If ($Valor == 81) {$Miles = "OCHENTA Y UNO MIL";}
        If ($Valor == 82) {$Miles = "OCHENTA Y DOS MIL";}
        If ($Valor == 83) {$Miles = "OCHENTA Y TRES MIL";}
        If ($Valor == 84) {$Miles = "OCHENTA Y CUATRO MIL";}
        If ($Valor == 85) {$Miles = "OCHENTA Y CINCO MIL";}
        If ($Valor == 86) {$Miles = "OCHENTA Y SEIS MIL";}
        If ($Valor == 87) {$Miles = "OCHENTA Y SIETE MIL";}
        If ($Valor == 88) {$Miles = "OCHENTA Y OCHO MIL";}
        If ($Valor == 89) {$Miles = "OCHENTA Y NUEVE MIL";}
        If ($Valor == 90) {$Miles = "NOVENTA MIL";}
        If ($Valor == 91) {$Miles = "NOVENTA Y UNO MIL";}
        If ($Valor == 92) {$Miles = "NOVENTA Y DOS MIL";}
        If ($Valor == 93) {$Miles = "NOVENTA Y TRES MIL";}
        If ($Valor == 94) {$Miles = "NOVENTA Y CUATRO MIL";}
        If ($Valor == 95) {$Miles = "NOVENTA Y CINCO MIL";}
        If ($Valor == 96) {$Miles = "NOVENTA Y SEIS MIL";}
        If ($Valor == 97) {$Miles = "NOVENTA Y SIETE MIL";}
        If ($Valor == 98) {$Miles = "NOVENTA Y OCHO MIL";}
        If ($Valor == 99) {$Miles = "NOVENTA Y NUEVE MIL";}
        return $Miles;
    }

}
