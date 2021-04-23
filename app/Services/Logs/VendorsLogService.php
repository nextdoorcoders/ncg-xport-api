<?php


namespace App\Services\Logs;


use App\Models\VendorsLog;

class VendorsLogService
{
    /**
     * @param string $message
     * @param string $vendorService
     * @param null $httpCode
     */
    public static function write(string $message, string $vendorService, $httpCode = null)
    {
        VendorsLog::create([
            'message' => $message,
            'vendor_service' => $vendorService,
            'http_code' => $httpCode
        ])->save();

    }
}