<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $kantin = [
            'nama'    => 'KantinKita',
            'lokasi'  => 'Gedung Selaru, Lantai 1',
            'jam'     => '07.00 – 17.00 WIB',
        ];
 
        return view('beranda', ['kantin' => $kantin]);
    }
}
