@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card-pro p-4 mb-4">
            <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                <div class="student-avatar" style="width: 56px; height: 56px; font-size: 1.5rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0;">
                        {{ $user->name }}
                    </h1>
                    <div style="font-size: 0.85rem; color: #64748b;">
                        Siswa Kelas {{ $user->kelas ?? '-' }} • NISN: {{ $user->nisn ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div style="font-size: 0.75rem; color: #64748b;">Email Terdaftar</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">{{ $user->email }}</div>
                </div>
                <div class="col-12 col-sm-6">
                    <div style="font-size: 0.75rem; color: #64748b;">Tempat, Tanggal Lahir</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">
                        {{ $user->tempat_lahir ? $user->tempat_lahir . ', ' : '' }}{{ $user->tanggal_lahir ?? '-' }}
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div style="font-size: 0.75rem; color: #64748b;">Status Tes Minat RIASEC</div>
                    <div>
                        @if($careerResult)
                            <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.82rem;">
                                Selesai ({{ $careerResult->holland_code }})
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1" style="font-size: 0.82rem;">
                                Belum Mengerjakan
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div style="font-size: 0.75rem; color: #64748b;">Status Rencana Kelulusan</div>
                    <div>
                        @if($pilihan)
                            <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.82rem;">
                                Selesai ({{ ucfirst($pilihan->rencana) }})
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1" style="font-size: 0.82rem;">
                                Belum Memilih
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <a href="{{ route('siswa.pilihan-saya') }}" class="btn btn-primary btn-sm fw-bold">
                    <i class="bi bi-file-earmark-person me-1"></i> Lihat Bukti Profil Digital
                </a>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
