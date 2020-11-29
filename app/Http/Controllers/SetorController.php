<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use Illuminate\Http\Request;

class SetorController extends Controller
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
        $setores = [];

        $busca = $this->request->get('busca');
        $per_page = $this->request->get('per_page');

        try {
            // listando todos os registros
            $setores = Setor::orderBy('nome');

            // realizando busca
            if (!empty($busca)) {
                $setores->where('nome', 'LIKE', "%" . $busca . "%")
                    ->orWhere('ramal', 'LIKE', "%" . $busca . "%")
                    ->orWhere('email', 'LIKE', "%" . $busca . "%");
            }

            if ($per_page == 'all') {
                $setores = $setores->paginate(99999);
            } else {
                $setores = $setores->paginate(10);
            }

            return $this->retorno('ok', null, $setores);
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

        $setor = [];

        try {
            // listando todos os registros
            $setor = Setor::find($id);

            if (empty($setor)) {

                return $this->retornoError("Registro não encontrado");
            }

            return $this->retorno('ok', null, $setor);
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

        $setor = [];

        $this->validacoes = [
            'regras' => [
                'nome' => 'required',
                'ramal' => 'required',
            ],
            'retornos' => []
        ];

        $validator = $this->validarDados();

        try {
            if (!$validator['status']) {

                return $this->retornoError($validator['errors']);
            }

            // criando um registros
            $setor = Setor::create($this->request->all());

            return $this->retorno('ok', null, $setor);
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

        $setor = [];

        $this->validacoes = [
            'regras' => [
                'nome' => 'required',
                'ramal' => 'required',
            ],
            'retornos' => []
        ];

        $validator = $this->validarDados();

        try {
            if (!$validator['status']) {

                return $this->retornoError($validator['errors']);
            }

            // criando um registros
            $setor = Setor::find($id);

            if (empty($setor)) {

                return $this->retornoError("Registro não encontrado");
            }

            $setor->update($this->request->all());

            return $this->retorno('ok', null, $setor);
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
            $setor = Setor::find($id);

            if (empty($setor)) {

                return $this->retornoError("Registro não encontrado");
            }

            // removendo registro
            $setor->delete();

            return $this->retorno('ok');
        } catch (\Exception $e) {

            return $this->retornoError($e->getMessage());
        }
    }
}
