<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use Illuminate\Http\Request;

class ContatoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * index
     *
     */
    public function index()
    {
        $contatos = [];

        $busca = $this->request->get('busca');
        $per_page = $this->request->get('per_page');
        $sort_by = $this->request->get('sort_by');
        $sort_desc = $this->request->get('sort_desc') ?? "false";

        if (empty($sort_by)) {
            $sort_by = 'contatos.nome';
        }

        $querySort = $sort_by . " " . (($sort_desc == "true") ? "DESC" : "ASC");

        try {
            // listando todos os registros
            $fields = [
                'contatos.id',
                'contatos.nome',
                'contatos.ramal',
                'setores.nome AS setor_nome',
            ];

            $contatos = Contato::select($fields)
                ->leftJoin('setores', 'setores.id', 'contatos.setor_id')
                ->orderByRaw($querySort);

            // realizando busca
            if (!empty($busca)) {
                $contatos->where('contatos.nome', 'LIKE', "%" . $busca . "%")
                    ->orWhere('contatos.ramal', 'LIKE', "%" . $busca . "%")
                    ->orWhere('contatos.email', 'LIKE', "%" . $busca . "%")
                    ->orWhere('setores.nome', 'LIKE', "%" . $busca . "%")
                    ->orWhere('setores.ramal', 'LIKE', "%" . $busca . "%");
            }

            if ($per_page == 'all') {
                $contatos = $contatos->paginate(99999);
            } else {
                $contatos = $contatos->paginate(10);
            }

            return $this->retorno('ok', null, $contatos);
        } catch (\Exception $e) {

            return $this->retornoError($e->getMessage());
        }
    }

    /**
     *
     * show
     *
     */
    public function show($id)
    {

        $contato = [];

        try {
            // listando todos os registros
            $contato = Contato::with('setor:id,nome,ramal')->find($id);

            if (empty($contato)) {

                return $this->retornoError("Registro não encontrado");
            }

            return $this->retorno('ok', null, $contato);
        } catch (\Exception $e) {

            return $this->retornoError($e->getMessage());
        }
    }

    /**
     *
     * criar
     *
     */
    public function store()
    {

        $contato = [];

        $this->validacoes = [
            'regras' => [
                'nome' => 'required',
                'ramal' => 'required',
                'setor_id' => 'required',
            ],
            'retornos' => []
        ];

        $validator = $this->validarDados();

        try {
            if (!$validator['status']) {

                return $this->retornoError($validator['errors']);
            }

            // criando um registros
            $contato = Contato::create($this->request->all());

            return $this->retorno('ok', null, $contato);
        } catch (\Exception $e) {

            return $this->retornoError($e->getMessage());
        }
    }

    /**
     *
     * atualizar
     *
     */
    public function update($id)
    {

        $contato = [];

        $this->validacoes = [
            'regras' => [
                'nome' => 'required',
                'ramal' => 'required',
                'setor_id' => 'required',
            ],
            'retornos' => []
        ];

        $validator = $this->validarDados();

        try {
            if (!$validator['status']) {

                return $this->retornoError($validator['errors']);
            }

            // criando um registros
            $contato = Contato::find($id);

            if (empty($contato)) {

                return $this->retornoError("Registro não encontrado");
            }

            $contato->update($this->request->all());

            return $this->retorno('ok', null, $contato);
        } catch (\Exception $e) {

            return $this->retornoError($e->getMessage());
        }
    }

    /**
     *
     * show
     *
     */
    public function destroy($id)
    {

        try {
            // listando todos os registros
            $contato = Contato::find($id);

            if (empty($contato)) {

                return $this->retornoError("Registro não encontrado");
            }

            // removendo registro
            $contato->delete();

            return $this->retorno('ok');
        } catch (\Exception $e) {

            return $this->retornoError($e->getMessage());
        }
    }
}
