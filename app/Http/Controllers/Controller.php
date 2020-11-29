<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * retornos de mensagens
     *
     */
    public static $retornos = [
        '500'             => '500',
        'OK'              => 'OK',
        'ERRO_404'        => 'data not found',
        'ERRO'            => 'err',
        'LOGIN_OK'        => 'login ok',
        'ERRO_LOGIN'      => 'error authentication',
        'DADOS_INVALIDOS' => 'invalid data',
    ];

    /**
     *
     * validações
     *
     */
    protected $validacoes = [
        'regras'   => [],
        'retornos' => [],
    ];

    /**
     *
     * request
     *
     */
    protected $request;

    /**
     *
     * construct
     *
     */
    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    /**
     *
     * retorno json
     *
     */
    public function retorno($status, $message = null, $data = null, $statusCode = 200, $errors = [])
    {

        return response()->json(
            [
                'status'  => $status,
                'message' => (!empty($message)) ? static::$retornos[$message] : $message,
                'data'    => ($status == 'ok') ? (is_object($data) ? $data : (object)[]) : (object)[],
                'errors'  => $errors,
            ],
            $statusCode
        );
    }

    /**
     *
     * return error
     *
     */
    public function retornoError($error)
    {

        return response()->json(['status' => 'error', 'message' => null, 'data' => (object)[], 'errors' => (is_array($error) ? $error : [$error])], 400);
    }

    /**
     *
     * validar dados
     *
     */
    public function validarDados($parcial = false)
    {

        if ($parcial) {
            $validacoes = [];
            foreach ($this->validacoes['regras'] as $campo => $validacao) {
                if (array_key_exists($campo, $this->request->all())) {
                    $validacoes[$campo] = $validacao;
                }
            }

            $this->validacoes['regras'] = $validacoes;
        }

        $validator = Validator::make($this->request->all(), $this->validacoes['regras'], $this->validacoes['retornos']);
        if ($validator->fails()) {

            $ret = ['status' => false, 'errors' => $validator->errors()->all()];
        } else {

            $ret = ['status' => true];
        }

        return $ret;
    }

    /**
     *
     * status
     *
     */
    public function status()
    {

        return ['status' => true];
    }
}
