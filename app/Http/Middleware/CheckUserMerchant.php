<?php

namespace App\Http\Middleware;

use App\Models\Payroll\Employee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckUserMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $employee = Employee::query()->firstWhere('user_id', auth()->id());

        if ($employee == null) {
            return response()->json([
                'success' => false,
                'error' => 'unrelated_employee',
                'error_description' => "You aren't related in any merchant",
                'response_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
