<?php

namespace App\Exceptions;

use App\Exceptions\InvalidOrderException;
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
        public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Không tìm thấy tài nguyên!'], 404);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json(['message' => 'Không tìm thấy trang!'], 404);
        }

        return parent::render($request, $exception);
    }

    public function register(): void
    {
        $this->renderable(function (InvalidOrderException $e, Request $request) {
            return response()->view('errors.invalid-order', [], 500);
        });
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 404);
            }
        });
    }

    protected function context(): array
        {
    return array_merge(parent::context(), [
        'foo' => 'bar',
    ]);
        }
    public function report(Throwable $exception)
    {
    // Ghi log khi exception xảy ra
    \Log::error('Lỗi xảy ra: ' . $exception->getMessage());

    parent::report($exception);
    }
}
