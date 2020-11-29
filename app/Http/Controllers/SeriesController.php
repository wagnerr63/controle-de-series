<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Services\{CriadorDeSerie, RemovedorDeSerie};
use App\Serie;
use App\Temporada;
use App\Episodio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    public function index(Request $request) {
        $series = Serie::query()
            ->orderBy('nome')
            ->get();
        $mensagem = $request->session()->get('mensagem');

        return view('series.index', compact('series', 'mensagem'));
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(
        SeriesFormRequest $request,
        CriadorDeSerie $criadorDeSerie
    ) {
        $serie = $criadorDeSerie->criarSerie(
            $request->nome,
            $request->qtd_temporadas,
            $request->ep_por_temporada
        );
    
        $request->session()
            ->flash(
                'mensagem',
                "Série {$serie->id} e suas temporadas e episódios criados com sucesso {$serie->nome}"
            );
    
        return redirect()->route('listar_series');
    }

    public function destroy(Request $request, RemovedorDeSerie $removedorDeSerie)
    {   
        $serieNome = $removedorDeSerie->removerSerie($request->id);

        $request->session()
            ->flash(
                'mensagem',
                "Série $serieNome removida com sucesso"
            );
        return redirect()->route('listar_series');
    }

    public function editaNome(int $id ,Request $request){
        $novoNome = $request->nome;
        $serie = Serie::find($id);

        $serie->nome = $novoNome;
        $serie->save(); 
    }
}
