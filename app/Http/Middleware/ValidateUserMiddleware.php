<?php

namespace App\Http\Middleware;

use App\Exceptions\LoginFailedException;
use App\Exceptions\LoginUnAuthorizeException;
use App\Models\User;
use App\Utils\ResponseUtil;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     * @throws LoginFailedException
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (! $user->email_verified_at) {
            return response()->json(ResponseUtil::generateError('UNAUTHORIZED','Your account is not verified.',
                'Your account is not verified.'), Response::HTTP_UNAUTHORIZED);
        }

        if (! $user->is_active) {
            return response()->json(ResponseUtil::generateError('UNAUTHORIZED','Your account is deactivated. please contact your administrator.',
                'Your account is deactivated. please contact your administrator.'), Response::HTTP_UNAUTHORIZED);

        }

        $roles = $user->getRoleNames();
        if (!$roles->count()) {
            throw new LoginUnAuthorizeException('You have not assigned any role');
        }
        $role = $roles->first();
        $platform = $this->getPlatformNameFromUrl($request->url());
        if (!in_array(User::PLATFORM[$platform], User::LOGIN_ACCESS[$role])) {
            throw new LoginUnAuthorizeException('you are unable to access this platform');
        }

        return $next($request);
    }

    public function getPlatformNameFromUrl($url){

        $platform = '';
        switch ($url)
        {
            case (\Str::contains($url,'api/admin')):
                $platform =  'admin';
                break;
            case (\Str::contains($url,'api/device')):
                $platform =  'device';
                break;
            case (\Str::contains($url,'api/desktop')):
                $platform =  'desktop';
                break;
            case (\Str::contains($url,'api/client')):
                $platform =  'client';
                break;
        }

        return $platform;
    }
}
