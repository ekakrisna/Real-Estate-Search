<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Models\ErrorLog;

class ErrorLogs extends ExceptionHandler
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report(){
        
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception){
        $log = new ErrorLog;
        $log->message = $exception;
        $log->stack_trace = $request->fullUrl();
        $log->save();
        return \Redirect::back()->with('error', 'Something Went Wrong.');
    }
}