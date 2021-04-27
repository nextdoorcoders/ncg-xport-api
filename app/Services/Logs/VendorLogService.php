<?php


namespace App\Services\Logs;


use App\Models\VendorLog;

class VendorLogService
{
    /**
     * @param string $message
     * @param string $vendorService
     * @param null $httpCode
     */
    public static function write(string $message, string $vendorService, $httpCode = null)
    {
        VendorLog::create([
            'message' => $message,
            'vendor_service' => $vendorService,
            'http_code' => $httpCode
        ])->save();

    }
}