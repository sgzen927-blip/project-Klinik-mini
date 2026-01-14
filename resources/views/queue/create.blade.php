@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white font-weight-bold">Formulir Pendaftaran Antrian</div>

                <div class="card-body">
                    {{-- Menampilkan Pesan Error jika terjadi duplikasi atau kesalahan validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal Mendaftar:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('queue.store') }}">
                        @csrf

                        {{-- PERBAIKAN: Input Nama Pasien Manual --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pasien</label>
                            <input type="text" name="patient_name" 
                                   class="form-control @error('patient_name') is-invalid @enderror" 
                                   placeholder="Masukkan nama lengkap pasien..." 
                                   value="{{ old('patient_name') }}" required autofocus>
                            <div class="form-text text-muted">Contoh: Budi Santoso</div>
                            @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Dokter</label>
                            <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Dokter Spesialis --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} ({{ $doctor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Kunjungan</label>
                            <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" 
                                   value="{{ old('visit_date', date('Y-m-d')) }}" required>
                            @error('visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Keluhan (Minimal 10 Karakter)</label>
                            <textarea name="complaint" class="form-control @error('complaint') is-invalid @enderror" 
                                      rows="3" placeholder="Jelaskan keluhan Anda..." required>{{ old('complaint') }}</textarea>
                            @error('complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success py-2">Ambil Nomor Antrian</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection