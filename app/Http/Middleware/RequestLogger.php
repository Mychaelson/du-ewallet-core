<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Promotions\ApiLog;

class RequestLogger
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
        // Pre-Middleware Action
        if (config('app.api_logger') == true)
        {
            $body = $request->all(); //json_decode( $request->all(), true);
            $logBody = [];
            if(is_array($body))
            {
                foreach ($body as $key => $value) {
                    if($key == 'password' || $key == 'pin')
                    {
                        $logBody[$key] = "******";
                    }
                    else
                    {
                        $logBody[$key] = $value;
                    }
                }
            }

            $apiLog = new ApiLog();
            $apiLog->request_method = $request->method();
            $apiLog->request_endpoint = $request->path();
            $apiLog->request_header = json_encode($request->headers->all());
            $apiLog->request_body = (count($logBody) > 0) ? json_encode($logBody) : (json_encode($body) ?? '[]');
            $apiLog->save();
        }

        $response = $next($request);

        // Post-Middleware Action
        if (config('app.api_logger') == true)
        {
            if (isset($apiLog))
            {
                $apiLog->response_header = json_encode($response->headers->all());
                $apiLog->response_body = $response->content();
                $apiLog->save();
            }
        }

        return $response;
    }
}
