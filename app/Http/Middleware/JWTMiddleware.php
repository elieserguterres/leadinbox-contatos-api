<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

use App\Models\User;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = $request->bearerToken();

        if (!$token) {

            return response()->json(['status' => 'error', 'message' => null, 'data' => [], 'errors' => ['Precisa de token!']], 401);
        }

        try {

            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {

            return response()->json(['status' => 'error', 'message' => null, 'data' => [], 'errors' => [$e->getMessage()]], 401);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => null, 'data' => [], 'errors' => [$e->getMessage()]], 401);
        }

        // recupera usuario do token
        $user = User::find($credentials->sub->id);

        // caso o usuario nao exista mais, retorno erro
        if (empty($user)) {

            return response()->json(['status' => 'error', 'message' => null, 'data' => [], 'errors' => ['Usuário inválido']], 400);
        }

        // incopora user no request
        $request->user = $user;

        // segue o baile
        return $next($request);
    }
}
