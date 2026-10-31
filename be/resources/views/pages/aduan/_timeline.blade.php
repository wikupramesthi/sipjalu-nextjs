{{-- Riwayat tindak lanjut (terlihat publik di detail & pelacakan) --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-tools me-1"></i> Riwayat Tindak Lanjut
            <span class="badge bg-primary ms-1">{{ $item->tindakLanjuts->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @forelse ($item->tindakLanjuts as $tl)
        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <strong>{{ $tl->tanggal?->format('d M Y H:i') ?? '-' }}</strong>
                    @if ($tl->status)
                    @php
                    $badge = ['menunggu' => 'secondary','diverifikasi' => 'info','diproses' => 'warning','selesai' => 'success','ditolak' => 'danger'][$tl->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $badge }}">Status: {{ ucfirst($tl->status) }}</span>
                    @endif
                    <small class="text-muted">oleh {{ $tl->user->name ?? 'Petugas' }}</small>
                </div>
                @can('aduans.tindaklanjut.destroy')
                <a onclick="confirmHapusTindakLanjut('{{ $tl->uuid }}')" title="Hapus tindak lanjut"
                    class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </a>
                <form id="hapusTlForm_{{ $tl->uuid }}"
                    action="{{ route('aduans.tindak-lanjut.destroy', $tl->uuid) }}" method="POST" class="d-none">
                    @method('DELETE')
                    @csrf
                </form>
                @endcan
            </div>

            <p class="mb-2" style="white-space: pre-wrap;">{{ $tl->catatan }}</p>

            @if ($tl->foto_1 || $tl->foto_2)
            <div class="row g-2">
                @foreach (['foto_1', 'foto_2'] as $field)
                @if ($tl->{$field})
                <div class="col-6 col-md-4">
                    <a href="{{ asset('storage/' . $tl->{$field}) }}" target="_blank">
                        <img src="{{ asset('storage/' . $tl->{$field}) }}" class="img-fluid rounded border"
                            style="max-height: 140px; width: 100%; object-fit: cover;" alt="Foto tindak lanjut">
                    </a>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <p class="text-muted mb-0">Belum ada tindak lanjut untuk aduan ini.</p>
        @endforelse
    </div>
</div>

<script>
    function confirmHapusTindakLanjut(getId) {
        Swal.fire({
            title: 'Hapus Tindak Lanjut?',
            text: 'Catatan dan foto tindak lanjut ini akan dihapus permanen. Status aduan tidak dikembalikan otomatis.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusTlForm_' + getId).submit();
            }
        });
    }
</script>
