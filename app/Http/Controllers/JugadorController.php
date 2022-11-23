<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jugador;
use App\Models\torneo;

class JugadorController extends Controller
{
    public function index (){
        $jugadores = jugador::get();
        return  $jugadores;        
    }
    
    private function drawVacio($cantidadPartidos){
        $draw=[];
        for ($i=0; $i < $cantidadPartidos ; $i++) { 
            $partido = [];            
            $partido['partidoNro'] = $i;
            $partido['jugador1ID'] = '';
            $partido['jugador1Nombre'] = '';
            $partido['jugador1Valor'] = '';
            $partido['jugador1Suerte'] = 0;
            $partido['jugador2ID'] = '';
            $partido['jugador2Nombre'] = '';
            $partido['jugador2Valor'] = '';
            $partido['jugador2Suerte'] = 0;
            $partido['partidoResultado'] = '';
            $draw[]= $partido;            
        }
        return($draw);
    }

    private function extraigoGanadores($ronda){
        $jugadores=[];
        foreach ($ronda as $partido) {
            $ganador=[];
            if ($partido['partidoResultado']=='1') {
                $ganador['id'] = $partido['jugador1ID'];
                $ganador['nombre'] = $partido['jugador1Nombre'];
                $ganador['valor'] = $partido['jugador1Valor'];
            }else{
                $ganador['id'] = $partido['jugador2ID'];
                $ganador['nombre'] = $partido['jugador2Nombre'];
                $ganador['valor'] = $partido['jugador2Valor'];
            }
            $jugadores[]=$ganador;
        }
        return($jugadores);
    }


    private function simulaRonda($ronda, $jugadores){
        $i=0;
        foreach ($jugadores as $jugador) {            
            $partidoNro = floor($i/2);
            if ($ronda[$partidoNro]['jugador1ID'] == '') {
                $ronda[$partidoNro]['jugador1ID'] = $jugador['id'];
                $ronda[$partidoNro]['jugador1Nombre'] = $jugador['nombre'];
                $ronda[$partidoNro]['jugador1Valor'] = $jugador['valor'];
            }else{
                $ronda[$partidoNro]['jugador2ID'] = $jugador['id'];
                $ronda[$partidoNro]['jugador2Nombre'] = $jugador['nombre'];
                $ronda[$partidoNro]['jugador2Valor'] = $jugador['valor'];
                $suerteJugador = rand(1,2);
                
                if($suerteJugador ==1){
                    $ronda[$partidoNro]['jugador1Suerte'] = 10;
                }
                else{
                    $ronda[$partidoNro]['jugador1Suerte'] = 10;                               
                }
                $scoreJugador1 = $ronda[$partidoNro]['jugador1Valor'] + $ronda[$partidoNro]['jugador1Suerte'];
                $scoreJugador2 = $ronda[$partidoNro]['jugador2Valor'] + $ronda[$partidoNro]['jugador2Suerte'];
                
                if($scoreJugador1 > $scoreJugador2){
                    $ronda[$partidoNro]['partidoResultado'] = 1;                    
                }
                else{
                    $ronda[$partidoNro]['partidoResultado'] = 2;                    
                }
            }            
            $i=$i+1;
        }
        return ($ronda);
    }

    private function preparoJugadores($jugadoresBD){
        $jugadores=[];        
        
        foreach ($jugadoresBD as $jugadorBD) {
            $jugador=[];
            if($jugadorBD->genero =="M"){
                $valor= $jugadorBD->habilidad + $jugadorBD->fuerza +$jugadorBD->velocidad;
            }else{
                $valor= $jugadorBD->habilidad + $jugadorBD->tiemporeaccion;
            }
            $jugador['id'] = $jugadorBD->id;
            $jugador['nombre'] = $jugadorBD->nombre;
            $jugador['valor'] = $valor;
            $jugadores[] = $jugador;                            
        }
        return($jugadores);
    }
    
    public function torneo (Request $request){
        if(isset($request->genero)){            
            switch ($request->genero) {
                case 'M':
                    $genero= $request->genero;
                    break;
                case 'F':
                    $genero= $request->genero;
                    break;                    
                default:
                    return('Género del torneo inválido');
                    break;
            }
        }else{            
            return('Género del torneo Indefinido');
        }
        
        if(isset($request->detallePartidos)){            
            switch ($request->detallePartidos) {
                case 'S':
                    $detallePartidos= $request->detallePartidos;
                    break;
                case 'N':
                    $detallePartidos= $request->detallePartidos;
                    break;                    
                default:
                    return('Código Detalle Partidos Incorrecto');
                    break;
            }
        }else{            
            $detallePartidos='N';
        }

        
        $jugadoresBD = jugador::where('genero', '=', $genero)->inRandomOrder()->get();    
        $torneo=[];
        $rondaEncuentros = ['16','8','4','2','1'];

        $i=1;
        foreach ($rondaEncuentros as $encuentro) {
            if($i==1){
                $jugadores = $this->preparoJugadores($jugadoresBD);
            }
            $ronda = $this->drawVacio($encuentro);
            $ronda =$this->simulaRonda($ronda, $jugadores);
            $torneo['ronda_'. $i] = $ronda;
            $jugadores = $this->extraigoGanadores($ronda);
            $i=$i+1;
        }
        $campeon =[];
        $campeon['id'] =$jugadores[0]['id'];
        $campeon['nombre'] =$jugadores[0]['nombre'];
        $campeon['genero'] =$genero;
        $torneo['campeon'] = $campeon;

        $torneoBD = new torneo([
            'jugador_id' => $campeon['id'],
            'jugador_nombre' => $campeon['nombre'],
            'jugador_genero' => $campeon['genero'],
            'torneofecha' => date("Y-m-d H:i:s"),
            
        ]);                
        $torneoBD->save();

        if ($detallePartidos == 'S') {
            return($torneo);
        }else{
            return($campeon);
        }
        
    }

}
