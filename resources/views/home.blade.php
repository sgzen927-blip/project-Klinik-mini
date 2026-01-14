@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Selamat Datang, {{ Auth::user()->name }}</div>
                <div class="card-body text-center">
                    <h4>Sistem Antrian Klinik Mini</h4>
                    <p>Silakan pilih menu di bawah ini untuk memulai:</p>
                    <hr>
                    <div class="d-grid gap-3 d-md-block">
                        <a href="{{ route('queue.create') }}" class="btn btn-success btn-lg px-4">Daftar Antrian Baru</a>
                        <a href="{{ route('queue.index') }}" class="btn btn-outline-primary btn-lg px-4">Lihat Riwayat Saya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection