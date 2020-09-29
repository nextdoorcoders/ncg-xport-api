<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\CurrencyCollection;
use App\Services\Vendor\CurrencyService;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    /**
     * CurrencyController constructor.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @return CurrencyCollection
     */
    public function allCurrencies()
    {
        $response = $this->currencyService->allCurrencies();

        return new CurrencyCollection($response);
    }
}
