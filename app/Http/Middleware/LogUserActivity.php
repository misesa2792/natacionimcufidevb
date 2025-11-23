<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivityLog;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->id();
        $response = $next($request);

        try {
            $guestToken = $request->cookie('guest_token');

            if (!$guestToken) {
                $guestToken = bin2hex(random_bytes(16));
                cookie()->queue(cookie('guest_token', $guestToken, 43200));
            }

            $query   = $request->query();                          // array
            $payload = $request->except(['password', '_token']);   // array

            UserActivityLog::create([
                'iduser'      => $userId,
                'method'       => $request->method(),
                'url'          => $request->fullUrl(),
                'route_name'   => optional($request->route())->getName(),
                'ip'           => $request->ip(),
                'user_agent'   => substr($request->userAgent(), 0, 255),
                'query'        => !empty($query)
                                    ? json_encode($query, JSON_UNESCAPED_UNICODE)
                                    : null,
                'payload'     => !empty($payload)
                                    ? json_encode($payload, JSON_UNESCAPED_UNICODE)
                                    : null,
                'status_code'  => $response->getStatusCode(),
                'guest_token'  => $guestToken,
            ]);

            if (auth()->check()) {
                $user = auth()->user();

                // Para no escribir en cada request, sólo si han pasado, por ejemplo, 2 minutos
                if (!$user->last_activity_at || $user->last_activity_at->lt(now()->subMinutes(2))) {
                    $user->last_activity_at = now();
                    $user->save();
                }
            }
        } catch (\Throwable $e) {
            dd($e);
            // Evitar que un error del log rompa la app
        }

        return $response;
    }
}
