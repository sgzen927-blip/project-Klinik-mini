@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Daftar Antrian Pasien - Hari Ini</h5>
                    <span class="badge bg-white text-primary px-3 py-2 fs-6 shadow-sm">{{ date('d F Y') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center py-3" style="width: 120px;">No Antrian</th>
                                    <th>Nama Pasien</th>
                                    <th>Dokter</th>
                                    <th>Keluhan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($queues as $q)
                                <tr>
                                    <td class="text-center fw-bold text-primary fs-5">
                                        {{-- PERBAIKAN: Menggunakan queue_number asli dari database --}}
                                        #{{ $q->queue_number }}
                                    </td>
                                    <td>
                                        <div class="fw-bold text-uppercase">{{ $q->patient_name ?? $q->user->name }}</div>
                                        <small class="text-muted">Akun: {{ $q->user->name }}</small>
                                    </td>
                                    <td>
                                        <div class="text-dark fw-bold">{{ $q->doctor->name }}</div>
                                        <small class="badge bg-light text-dark border">{{ $q->doctor->specialization }}</small>
                                    </td>
                                    <td>
                                        <span class="text-muted" title="{{ $q->complaint }}">
                                            {{ Str::limit($q->complaint, 30) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($q->status == 'WAITING')
                                            <span class="badge rounded-pill bg-warning text-dark px-3 shadow-sm">Menunggu</span>
                                        @elseif($q->status == 'CALLED')
                                            <span class="badge rounded-pill bg-info text-white px-3 shadow-sm">Dipanggil</span>
                                        @elseif($q->status == 'DONE')
                                            <span class="badge rounded-pill bg-success px-3 text-white shadow-sm">Selesai</span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary px-3 shadow-sm">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if($q->status == 'WAITING')
                                                <form action="{{ route('admin.status-update', $q->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="CALLED">
                                                    <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                                                        <i class="bi bi-megaphone"></i> Panggil
                                                    </button>
                                                </form>
                                            @elseif($q->status == 'CALLED')
                                                <form action="{{ route('admin.status-update', $q->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="DONE">
                                                    <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm">
                                                        <i class="bi bi-check-circle"></i> Selesai
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-light btn-sm text-muted border px-3" disabled>
                                                    <i class="bi bi-archive"></i> Arsip
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
                                            <p class="mb-0">Tidak ada antrean terdaftar untuk hari ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection