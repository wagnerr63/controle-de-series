<?php

namespace App\Services;

use App\{Serie, Temporada, Episodio};
use Illuminate\Support\Facades\DB;

class RemovedorDeSerie{

    public function removerSerie(int $serieId) : string {
        $serieNome = '';

        DB::transaction(function() use ($serieId, &$serieNome){
            $serie = Serie::find($serieId); 
            $serieNome = $serie->nome;
            $this->removerTemporadas($serie);
            $serie->delete();
        });

        return $serieNome;

    }

    private function removerTemporadas(Serie $serie){
        $serie->temporadas->each(function(Temporada $temporada){
            $this->removerEpisodios($temporada);
            $temporada->delete();
        });
    }

    private function removerEpisodios($temporada){
        $temporada->episodios()->each(function(Episodio $episodio){
            $episodio->delete();
        });
    }

}