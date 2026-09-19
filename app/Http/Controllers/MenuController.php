<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menu = [
            ['nama' => 'Nasi Goreng',  'harga' => 15000, 'tersedia' => true],
            ['nama' => 'Mie Ayam',     'harga' => 13000, 'tersedia' => true],
            ['nama' => 'Es Teh Manis', 'harga' => 5000,  'tersedia' => true],
            ['nama' => 'Soto Ayam',    'harga' => 14000, 'tersedia' => false],
        ];
 
        return view('menu', ['menu' => $menu]);
    }
}