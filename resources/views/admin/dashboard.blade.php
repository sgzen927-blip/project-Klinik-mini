@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Alert Error jika ada --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Antrian Pasien - Hari Ini</h5>
                    <span class="badge bg-light text-primary">{{ date('d F Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">No Antrian</th>
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
                                    <td class="text-center fw-bold text-primary" style="font-size: 1.2rem;">
                                        #{{ $q->queue_number }}
                                    </td>
                                    <td>{{ $q->user->name }}</td>
                                    <td>{{ $q->doctor->name }}</td>
                                    <td><small class="text-muted">{{ Str::limit($q->complaint, 40) }}</small></td>
                                    <td class="text-center">
                                        @if($q->status == 'WAITING')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($q->status == 'CALLED')
                                            <span class="badge bg-success">Dipanggil</span>
                                        @else
                                            <span class="badge bg-secondary">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($q->status == 'WAITING')
                                            {{-- PERBAIKAN: Mengirimkan ID Antrian ($q->id) agar aksi spesifik per baris --}}
                                            <form action="{{ route('admin.status-update', $q->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="CALLED">
                                                <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                                                    Panggil
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>Selesai</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <p class="mb-0">Tidak ada antrian untuk hari ini.</p>
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