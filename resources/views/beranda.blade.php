@extends('layouts.app')
 
@section('judul', $kantin['nama'])
 
@section('isi')
    <h2>Selamat datang di {{ $kantin['nama'] }}</h2>
    <p>Lokasi: {{ $kantin['lokasi'] }}</p>
    <p>Jam buka: {{ $kantin['jam'] }}</p>
@endsection