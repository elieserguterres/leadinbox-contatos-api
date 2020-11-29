<?php

use Illuminate\Http\Request;

// Browser Preflight HTTP OPTIONS Method
$router->options('/{uri:.*}', function (Request $request) {
    $response = response(null, 200);

    if ($request->headers->has('Access-Control-Request-Headers')) {
        $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
    }

    return $response;
});

// login
$router->post('/login', 'AutenticacaoController@autenticar');

// rotas para marcas
$router->group(
    ['middleware' => 'jwt.auth'],
    function () use ($router) {

        // setores
        $router->resource('setores', 'SetorController');

        // contatos
        $router->resource('contatos', 'ContatoController');

        // usuários
        $router->resource('usuarios', 'UsuarioController');

        // autenticação
        $router->get('/login', 'AutenticacaoController@show');
    }
);
