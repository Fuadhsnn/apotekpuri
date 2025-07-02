<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle UTF-8 encoding errors
        if ($e instanceof \InvalidArgumentException && 
            str_contains($e->getMessage(), 'Malformed UTF-8 characters')) {
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Data encoding error',
                    'message' => 'Invalid characters detected in data'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan encoding data. Silakan coba lagi.');
        }

        return parent::render($request, $e);
    }
}
