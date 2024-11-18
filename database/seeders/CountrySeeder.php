<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = retry(3, function () {
            return Http::get('https://restcountries.com/v3.1/all');
        }, 1000);

        $countries = $response->json();

        $allIndependentCounties = Arr::where($countries, function ($country) {
            return array_key_exists('independent', $country) ? $country['independent'] : false;
        });

        foreach ($allIndependentCounties as $key => $country) {
            $currencies = $country['currencies'];
            $languages = $country['languages'];

            $currencyName = null;
            $currencySymbol = null;
            $language = null;

            // Extract currency name
            foreach ($currencies as $currency) {
                $currencyName = $currency['name'];
                break;
            }

            // Extract currency symbol
            foreach ($currencies as $currency) {
                $currencySymbol = array_key_exists('symbol', $currency) ? $currency['symbol'] : 'no symbol found';
                break;
            }

            // Extract language
            foreach ($languages as $lang) {
                $language = $lang;
                break;
            }

            try {
                DB::beginTransaction();
                DB::table('countries')->insert([
                    'name' => $country['name']['common'],
                    'code' => $country['cca2'],
                    'currency_name' => $currencyName,
                    'currency_symbol' => $currencySymbol,
                    'region' => $country['region'],
                    'language' => $language,
                    'population' => $country['population'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::commit();

            } catch (\Exception $exception) {
                DB::rollBack();
            }
        }

    }
}
