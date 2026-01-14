@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Antrian Saya</h5>
                    <a href="{{ route('queue.create') }}" class="btn btn-primary btn-sm">Daftar Antrian Baru</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Antrian</th>
                                    <th>Dokter</th>
                                    <th>Tanggal Periksa</th>
                                    <th>Keluhan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($queues as $item)
                                    <tr>
                                        <td><span class="badge bg-info text-dark">#{{ $item->queue_number }}</span></td>
                                        <td>{{ $item->doctor->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->visit_date)->format('d M Y') }}</td>
                                        <td>{{ Str::limit($item->complaint, 30) }}</td>
                                        <td>
                                            @if($item->status == 'WAITING')
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @elseif($item->status == 'CALLED')
                                                <span class="badge bg-success">Dipanggil</span>
                                            @else
                                                <span class="badge bg-secondary">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada riwayat pendaftaran antrian.</td>
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