<?php

namespace App\Services;

use App\Models\InvestmentAndSavings;
use Illuminate\Support\Facades\Http;

class GoldApiService
{
    protected $apiKey = "goldapi-6000u1sm26alp0a-io";
    protected $baseUrl = "https://www.goldapi.io/api/";

    public function getGoldPrices($symbol = 'XAU', $currency = 'EGP')
    {
        $url = "{$this->baseUrl}{$symbol}/{$currency}";

        $response = Http::withHeaders([
            'x-access-token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch gold prices.');
    }

    public function handle()
    {
        $gold = $this->getGoldPrices();

        \DB::transaction(function () use ($gold) {
            $this->updateGoldPrice('Gold 18', $gold['price_gram_18k']);
            $this->updateGoldPrice('Gold 21', $gold['price_gram_21k']);
            $this->updateGoldPrice('Gold 24', $gold['price_gram_24k']);
        });

        return true;
    }

    private function updateGoldPrice($name, $pricePerGram)
    {
        $investment = InvestmentAndSavings::where('name', $name)->first();
        if ($investment) {
            $investment->update([
                'amount' => $pricePerGram * floatval($investment->val),
            ]);
        }
    }
}
