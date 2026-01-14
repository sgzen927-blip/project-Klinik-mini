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
                <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                    <h5 class="mb-0 fw-bold">Riwayat Antrian Saya</h5>
                    <a href="{{ route('queue.create') }}" class="btn btn-primary btn-sm shadow-sm">
                        <i class="bi bi-plus-lg"></i> Daftar Antrian Baru
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 ps-4">No. Antrian</th>
                                    <th>Nama Pasien</th>
                                    <th>Dokter</th>
                                    <th>Tanggal Periksa</th>
                                    <th>Keluhan</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($queues as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-info text-dark fw-bold px-3">
                                                #{{ $item->queue_number }}
                                            </span>
                                        </td>
                                        {{-- Menampilkan Nama Pasien yang didaftarkan --}}
                                        <td class="fw-bold">{{ $item->patient_name ?? Auth::user()->name }}</td>
                                        <td>
                                            <div class="text-dark">{{ $item->doctor->name }}</div>
                                            <small class="text-muted">{{ $item->doctor->specialization }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->visit_date)->format('d M Y') }}</td>
                                        <td>
                                            <small class="text-muted" title="{{ $item->complaint }}">
                                                {{ Str::limit($item->complaint, 30) }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            @if($item->status == 'WAITING')
                                                <span class="badge rounded-pill bg-warning text-dark px-3">Menunggu</span>
                                            @elseif($item->status == 'CALLED')
                                                <span class="badge rounded-pill bg-primary px-3">Dipanggil</span>
                                            @elseif($item->status == 'DONE')
                                                <span class="badge rounded-pill bg-success px-3">Selesai</span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary px-3">Batal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                            Belum ada riwayat pendaftaran antrian.
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