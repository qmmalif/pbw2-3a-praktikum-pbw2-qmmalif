@extends('layouts.app')
 
@section('judul', 'Daftar Menu')
 
@section('isi')
    <h2>Daftar Menu</h2>
 
    <table border="1" cellpadding="6">
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Status</th>
        </tr>
        @foreach ($menu as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                <td>{{ $item['tersedia'] ? 'Tersedia' : 'Habis' }}</td>
            </tr>
        @endforeach
    </table>
 
    <p>Total menu: {{ count($menu) }}</p>
@endsection