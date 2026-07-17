<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTablePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        if ($id) {
            $table = \App\Models\StudyTable::find($id);
            if ($table && $table->password && $table->user_id !== auth()->id() && !auth()->user()->is_admin) {
                if (!session('table_password_auth_'.$id)) {
                    return response()->view('study-password', compact('table'));
                }
            }
        }
        
        return $next($request);
    }
}
