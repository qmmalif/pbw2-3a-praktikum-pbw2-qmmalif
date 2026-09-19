<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TentangController extends Controller
{
    public function index()
    {
        return view('tentang', [
            'nama'  => 'Qolbiyah Mualifah Muhammad',
            'nim'   => '607062500046',
            'kelas' => 'D3RPLA-49-02',
        ]);
    }
}
