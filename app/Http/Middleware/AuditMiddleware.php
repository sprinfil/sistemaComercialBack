<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Capturar la solicitud antes de la acción
        $response = $next($request);

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Obtener información de la solicitud
        $method = $request->method();
        $url = $request->url();
        $comment = $request->input('comment', ''); // Suponiendo que el comentario viene en la solicitud

        // Registrar en la tabla de auditoría
        AuditLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $method,
            'url' => $url,
            'comment' => $comment,
        ]);

        return $response;
    }
}
