@extends('layouts.app')
 
@section('judul', 'Tentang')
 
@section('isi')
    <p>Nama: {{ $nama }}</p>
    <p>NIM: {{ $nim }}</p>
    <p>Kelas: {{ $kelas }}</p>
@endsection