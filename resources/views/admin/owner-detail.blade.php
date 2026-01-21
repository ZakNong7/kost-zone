@extends('admin.layouts.admin')

@section('title', 'Detail Owner')
@section('page-title', 'Detail Owner')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.owners') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<!-- Owner Info -->
<div class="card-custom">
    <h5 class="mb-4">
        <i class="bi bi-person-circle"></i> Informasi Owner
    </h5>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Nama Lengkap</th>
                    <td>: {{ $owner->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>: {{ $owner->email }}</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>: {{ $owner->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status Email</th>
                    <td>: 
                        @if($owner->email_verified_at)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Terverifikasi
                            </span>
                            <br><small class="text-muted">Sejak: {{ $owner->email_verified_at->format('d M Y H:i') }}</small>
                        @else
                            <span class="badge bg-warning">
                                <i class="bi bi-clock"></i> Belum Verifikasi
                            </span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Terdaftar Sejak</th>
                    <td>: {{ $owner->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Terakhir Update</th>
                    <td>: {{ $owner->updated_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Total Kost</th>
                    <td>: <span class="badge bg-primary">{{ $owner->kosts->count() }} Kost</span></td>
                </tr>
                <tr>
                    <th>Kost Aktif</th>
                    <td>: <span class="badge bg-success">{{ $owner->kosts->where('is_active', true)->count() }} Kost</span></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Kost -->
<div class="card-custom mt-4">
    <h5 class="mb-4">
        <i class="bi bi-house-fill"></i> Daftar Kost ({{ $owner->kosts->count() }})
    </h5>
    
    @if($owner->kosts->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Nama Kost</th>
                    <th width="20%">Lokasi</th>
                    <th width="10%">Tipe</th>
                    <th width="15%">Harga/Bulan</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="15%" class="text-center">Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($owner->kosts as $index => $kost)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $kost->name }}</strong>
                        @if(count($kost->images) > 0)
                            <br><small class="text-muted">
                                <i class="bi bi-images"></i> {{ count($kost->images) }} Foto
                            </small>
                        @endif
                    </td>
                    <td>
                        <small>{{ Str::limit($kost->location, 40) }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $kost->type == 'Putra' ? 'primary' : ($kost->type == 'Putri' ? 'danger' : 'success') }}">
                            {{ $kost->type }}
                        </span>
                    </td>
                    <td>
                        <strong>Rp {{ number_format($kost->price, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center">
                        @if($kost->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <small>{{ $kost->created_at->format('d/m/Y') }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
        <p class="text-muted mt-2">Owner ini belum menambahkan kost</p>
    </div>
    @endif
</div>
@endsection