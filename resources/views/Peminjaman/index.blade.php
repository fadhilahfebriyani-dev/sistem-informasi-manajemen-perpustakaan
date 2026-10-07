@extends('layouts.app')

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-success { background:#16a34a; color:#fff; }
    .btn-success:hover { background:#15803d; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-edit { background:#fffbeb; color:#d97706; }
    .btn-edit:hover { background:#fef3c7; }
    .btn-del { background:#fef2f2; color:#dc2626; }
    .btn-del:hover { background:#fee2e2; }
    .btn-kembali { background:#eff6ff; color:#2563eb; }
    .btn-kembali:hover { background:#dbeafe; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; vertical-align:top; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }
    .actions { display:flex; gap:6px; align-items:center; flex-wrap:wrap; }
    .status-badge { display:inline-block; padding:3px 10px; border-radius:100px; font-size:11px; font-weight:500; }
    .status-dipinjam { background:#fffbeb; color:#d97706; }
    .status-dikembalikan { background:#f0fdf4; color:#16a34a; }
    .status-terlambat { background:#fef2f2; color:#dc2626; }
    .denda-badge { display:inline-block; padding:2px 8px; border-radius:100px; font-size:10px; font-weight:600; background:#fef2f2; color:#dc2626; margin-top:4px; }
    .buku-list { display:flex; flex-direction:column; gap:6px; }
    .buku-item { display:flex; flex-direction:column; gap:2px; padding:6px 8px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; }
    .buku-judul { font-size:12px; font-weight:600; color:#111827; }
    .buku-kode { font-size:11px; color:#2563eb; font-weight:500; background:#eff6ff; padding:1px 6px; border-radius:4px; display:inline-block; width:fit-content; font-family:monospace; }
    .buku-kode-empty { font-size:11px; color:#9ca3af; }
</style>
@endpush

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'success', title:'{{ session('success') }}', timer:2000, showConfirmButton:false });
});
</script>
@endif

@if(session('warning'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'warning', title:'Terlambat!', text:'{{ session('warning') }}', confirmButtonColor:'#dc2626' });
});
</script>
@endif

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Data Peminjaman</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Kelola data peminjaman buku</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-success">+ Peminjaman</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:45px;">No</th>
                <th>Anggota</th>
                <th>Buku & Kode</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th style="width:160px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $item)
            <tr>
                <td style="color:#9ca3af;">{{ $peminjaman->firstItem() + $loop->index }}</td>

                {{-- Anggota --}}
                <td>
                    <div style="font-weight:500;color:#111827;">{{ $item->anggota->nama ?? '-' }}</div>
                    <div style="font-size:12px;color:#6b7280;">{{ $item->anggota->kelas ?? '' }}</div>
                    {{-- Tampilkan badge denda jika ada --}}
                    @if($item->denda)
                        @if($item->denda->isLunas())
                            <span class="denda-badge" style="background:#f0fdf4;color:#16a34a;">Denda Lunas</span>
                        @else
                            <a href="{{ route('denda.show', $item->denda) }}" class="denda-badge">
                                Denda {{ $item->denda->total_denda_format }}
                            </a>
                        @endif
                    @endif
                </td>

                {{-- Buku & Kode --}}
                <td>
                    <div class="buku-list">
                        @forelse($item->details as $detail)
                        <div class="buku-item">
                            <span class="buku-judul">{{ $detail->buku->judul ?? '-' }}</span>
                            @if($detail->kode_buku)
                                <span class="buku-kode">{{ $detail->kode_buku }}</span>
                            @else
                                <span class="buku-kode-empty">Kode tidak tersedia</span>
                            @endif
                        </div>
                        @empty
                        <span style="color:#9ca3af;font-size:12px;">Tidak ada buku</span>
                        @endforelse
                    </div>
                </td>

                {{-- Tanggal Pinjam --}}
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>

                {{-- Tanggal Kembali --}}
                <td>
                {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                {{-- Tampilkan status keterlambatan / sisa hari jika masih dipinjam --}}
                    @if($item->status === 'dipinjam' && $item->tanggal_kembali)
                    @if($item->isTerlambat())
                <div style="font-size:11px;color:#dc2626;font-weight:500;">
                Terlambat {{ $item->getHariTerlambat() }} hari
                </div>
                @else
                @php
                    $sisaHari = (int) now()->startOfDay()->diffInDays(
                    $item->tanggal_kembali->copy()->startOfDay()
                    );
                @endphp
                @if($sisaHari <= 2)
                <div style="font-size:11px;color:#d97706;font-weight:500;">
                    {{ $sisaHari }} hari lagi
                </div>
                @endif
                @endif
                @endif
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $status = strtolower($item->status);
                        $cls = $status === 'dikembalikan' ? 'status-dikembalikan'
                             : ($status === 'terlambat'   ? 'status-terlambat'
                             : 'status-dipinjam');
                    @endphp
                    <span class="status-badge {{ $cls }}">{{ ucfirst($item->status) }}</span>
                </td>

                {{-- Aksi --}}
<td>
    <div class="actions">
        {{-- Tombol Kembalikan — hanya muncul jika status masih dipinjam --}}
        @if($item->status === 'dipinjam')
        <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" style="display:inline">
            @csrf
            <button type="button" class="btn btn-sm btn-kembali"
                onclick="Swal.fire({
                    title: 'Kembalikan Buku?',
                    text: 'Pastikan buku sudah diterima.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Kembalikan'
                }).then(r => { if(r.isConfirmed) this.closest('form').submit() })">
                Kembalikan
            </button>
        </form>
        @endif

        {{-- Status 'terlambat': buku sudah fisik kembali, tinggal menunggu pembayaran denda --}}
        @if($item->status === 'terlambat' && $item->denda)
        <a href="{{ route('denda.show', $item->denda) }}" class="btn btn-sm btn-kembali">
            Bayar Denda
        </a>
        @endif

        <a href="{{ route('peminjaman.edit', $item->id) }}" class="btn btn-sm btn-edit">Edit</a>

        <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-sm btn-del"
                onclick="Swal.fire({title:'Hapus data ini?',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonText:'Batal',confirmButtonText:'Ya, hapus'}).then(r=>{ if(r.isConfirmed) this.closest('form').submit() })">
                Hapus
            </button>
        </form>
    </div>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af;">Tidak ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding:0.875rem 1.25rem;border-top:1px solid #e5e7eb;">
        {{ $peminjaman->links() }}
    </div>
</div>

@endsection