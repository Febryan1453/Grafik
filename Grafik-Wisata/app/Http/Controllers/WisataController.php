<?php

namespace App\Http\Controllers;

use App\Charts\WisataChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WisataController extends Controller
{
    // https://www.kawankoding.id/membuat-grafik-sebaran-covid-19-dengan-laravel/
    public function index()
    {
        $wisata = collect(Http::get('https://ict-juara.herokuapp.com/api/wisata')->json());

        $labels = $wisata->flatten(1)->pluck('nama_wisata');
        $data   = $wisata->flatten(1)->pluck('harga');
        $colors = $labels->map(function ($item) {
            return $rand_color = '#' . substr(md5(mt_rand()), 0, 6);
        });

        // dd($data);
        $chart = new WisataChart;
        $chart->labels($labels);
        $chart->dataset('Harga Wisata', 'doughnut', $data)->backgroundColor($colors);

        return view('wisata', [
            'chart' => $chart,
        ]);
    }

    public function chart()
    {
        $response = Http::get('https://ict-juara.herokuapp.com/api/wisata');
        $respon = json_decode($response);

        $categories = [];
        $data = [];
        foreach ($respon->data as $wis) {
            $categories[] = $wis->nama_wisata;
            $data[] = $wis->harga;
        }

        // dd(json_encode($categories));
        return view('hight-chart', [
            'categories' => $categories,
            'data' => $data,
        ]);
    }
}
