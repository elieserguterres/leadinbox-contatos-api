<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Jobs\RecuperarSenhaJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class AutenticacaoController extends Controller
{

    protected $validacoes = [
        'regras'   => [
            'email'    => 'required|email',
            'password' => 'required',
        ],
        'retornos' => [
            'email.required'    => 'E-mail obrigatório',
            'email.email'       => 'E-mail inválido',
            'password.required' => 'Senha obrigatória',
        ],
    ];

    public function rota()
    {

        return "OK";
    }

    /**
     *
     * Atualização Token
     *
     */
    public function atualizarToken()
    {

        $token = $this->request->bearerToken();

        try {
            $decoded = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

            return $this->retorno('success', null, ['_token' => $token]);
        } catch (ExpiredException $e) {
            JWT::$leeway    = 720000;
            $decoded        = (array) JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $decoded['iat'] = time();
            $decoded['exp'] = time() + 60 * 60;

            $token = JWT::encode($decoded, env('JWT_SECRET'));

            return $this->retorno('ok', null, ['_token' => $token]);
        } catch (\Exception $e) {

            return $this->retorno('error', null, null, 400, ['Token inválido']);
        }
    }

    /**
     *
     * Autenticar
     *
     */
    public function autenticar()
    {

        // validando dados
        $validarAutenticacao = $this->validarDados();

        if (!$validarAutenticacao['status']) {

            return $this->retorno('error', null, null, 400, $validarAutenticacao['errors']);
        };

        // pegando email do request
        $email = $this->request->input('email');

        // localizando no banco
        $usuario = User::where('email', $email)->first();

        if (empty($usuario)) {

            return $this->retorno('error', null, null, 400, ["Registro não encontrado"]);
        }

        // proximo passo validando senha
        return $this->validarLogin($usuario);
    }

    /**
     *
     * validar login e criar o token de acesso
     *
     */
    private function validarLogin(User $usuario)
    {

        // verificar password
        if (Auth::attempt(['email' => $usuario->email, 'password' => $this->request->input('password')])) {

            // criando jwt
            return $this->retorno('ok', null, collect(['_token' => $this->jwt($usuario)]));
        }

        // retornar com erro
        return $this->retorno('error', null, null, 400, ['Senha incorreta', 'Senha inválida']);
    }

    /**
     *
     *  gerar novo token
     *
     */
    private function jwt(User $usuario)
    {

        $iss = env('APP_NAME', 'dev');
        $exp = env('JWT_EXPIRATION', '24'); // tempo em horas

        $parametros_token = [
            'iss' => $iss,
            'sub' => ['id' => $usuario->id],
            'iat' => time(),
            'exp' => time() + (60 * 60 * $exp), // Expiration time
        ];

        return JWT::encode($parametros_token, env('JWT_SECRET'));
    }

    /**
     * 
     * dados do login
     * 
     */
    public function show()
    {

        return $this->retorno('ok', null, $this->request->user);
    }
}
