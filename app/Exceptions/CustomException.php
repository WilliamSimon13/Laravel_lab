<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public function report()
    {
        // Báo cáo lỗi, ví dụ ghi log hoặc gửi email
        \Log::error('Custom Exception xảy ra: ' . $this->getMessage());
    }

    public function render($request)
    {
        return response()->json(['message' => 'Đã xảy ra lỗi tuỳ chỉnh!'], 400);
    }
}
