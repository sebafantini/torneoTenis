<?php

namespace App\Http\Controllers;

use App\Models\torneo;
use DateTime;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    public function index (){
        $torneos = torneo::get();
        return  $torneos;        
    }
    public function search (Request $request){
        if(isset($request->jugador_genero)){            
            switch ($request->jugador_genero) {
                case 'M':
                    $jugador_genero= $request->jugador_genero;
                    break;
                case 'F':
                    $jugador_genero= $request->jugador_genero;
                    break;                    
                default:
                    return('Género del torneo inválido');
                    break;
            }
        }else{            
            $jugador_genero='';
        }
        

        if(isset($request->jugador_id)){            
            $jugador_id = $request->jugador_id;                        
        }else{            
            $jugador_id='';
        }

        if(isset($request->desdeFecha)){                        
            if($this->validateDate($request->desdeFecha, 'Y-m-d')){
                $desdeFecha = $request->desdeFecha;          
            }
            else{
                return('Formato de fecha desde inválido, se espera [Y-m-d]');
            }
        }else{            
            $desdeFecha='';
        }
        
        if(isset($request->hastaFecha)){                                         
            if($this->validateDate($request->hastaFecha, 'Y-m-d')){
                $hastaFecha = $request->hastaFecha;                        
            }
            else{
                return('Formato de fecha hasta inválido, se espera [Y-m-d]');
            }
        }else{            
            $hastaFecha='';
        }
        
        
        $torneos = torneo::where(function($q) use($jugador_genero){
            if ($jugador_genero != '') {                                            
                $q->where('jugador_genero', '=',  $jugador_genero);
            }                                                                    
        })
        ->where(function($q) use($jugador_id){
            if ($jugador_id != '') {                                            
                $q->where('jugador_id', '=',  $jugador_id);
            }                                                                    
        })        
        ->where(function($q) use($desdeFecha){
            if ($desdeFecha != '') {                                            
                $q->where('torneofecha', '>=',  $desdeFecha);
            }                                                                    
        })
        ->where(function($q) use($hastaFecha){
            if ($hastaFecha != '') {                                            
                $q->where('torneofecha', '<=',  $hastaFecha);
            }                                                                    
        })
        ->get();

        return  $torneos;        
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);         
        return $d && $d->format($format) == $date;
    }

}
