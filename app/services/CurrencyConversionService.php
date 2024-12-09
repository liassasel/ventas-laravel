<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConversionService
{
    public function getSolesToDollarRate()
    {
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/PEN');

        if ($response->successful()){
            $data = $response->json();
            return $data['rates']['USD'];
        }
        return null;
    }

    public function convertSolesToDollars($soles)
    {
        $rate = $this->getSolesToDollarRate();
        return $rate ? $soles * $rate : null;
    }

    public function convertDollarsToSoles($dollars)
    {
        $rate = $this->getSolesToDollarRate();
        return $rate ? $dollars / $rate : null;
    }
}