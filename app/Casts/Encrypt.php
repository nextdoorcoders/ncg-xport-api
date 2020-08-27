<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Encrypt implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model  $model
     * @param string $key
     * @param        $inputValue
     * @param array  $attributes
     * @return mixed
     */
    public function get($model, $key, $inputValue, $attributes)
    {
        $inputValueCheck = json_decode($inputValue, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $inputValue = $inputValueCheck;
        }

        if (is_array($inputValue)) {
            foreach ($inputValue as $key => $value) {
                try {
                    $inputValue[$key] = decrypt($value);
                } catch (DecryptException $exception) {
                    Log::error($exception);
                }
            }
        } else {
            try {
                $inputValue = decrypt($inputValue);
            } catch (DecryptException $exception) {
                Log::error($exception);
            }
        }

        return $inputValue;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model  $model
     * @param string $key
     * @param        $inputValue
     * @param array  $attributes
     * @return mixed
     */
    public function set($model, $key, $inputValue, $attributes)
    {
        if (is_array($inputValue)) {
            foreach ($inputValue as $key => $value) {
                try {
                    $inputValue[$key] = encrypt($value);
                } catch (EncryptException $exception) {
                    Log::error($exception);
                }
            }

            $inputValue = json_encode($inputValue);
        } else {
            try {
                $inputValue = encrypt($inputValue);
            } catch (EncryptException $exception) {
                Log::error($exception);
            }
        }

        return $inputValue;
    }
}
