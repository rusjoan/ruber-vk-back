<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class VkAuthMiddleware
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
        if (Session::get('access_token') && Session::get('viewer_id')) {
            return $next($request);
        }

        $validator = Validator::make($request->all(), [
            'api_id' => 'required|int',
            'viewer_id' => 'required|int',
            'auth_key' => 'required|string',
            'viewer_type' => 'int',
            'access_token' => 'string'
        ]);

        if ($validator->fails()) {
            \Log::debug(__METHOD__ . ' validation rules failed');
            return $next($request);
        }

        $params = collect($request->only(['api_id', 'viewer_id']))
            ->push(env('API_SECRET'))
            ->toArray();

        if (md5(implode("_", $params)) !== $request->get('auth_key')) {
            \Log::debug(__METHOD__ . ' key check failed', $params);
            return $next($request);
        }

        Session::put('access_token', $request->get('access_token'));
        Session::put('viewer_id', $request->get('viewer_id'));

        return $next($request);
    }
}
