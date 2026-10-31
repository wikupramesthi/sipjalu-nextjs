@extends('layouts.app')
@section('title', 'Dokumen')
@section('breadcrumb')
<x-breadcrumb title="Dokumen" page="Dokumen" active="Daftar Dokumen" route="{{ route('documents.index') }}" />
@endsection
@section('content')
@if(session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
@if(session('error'))<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

<section class="section">
    {{-- Kartu statistik: angka mengikuti data hasil filter --}}
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-xl-3"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-file"></i></div><div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Dokumen</div></div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['active']) }}</div><div class="ml-stat-label">Aktif</div></div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['inactive']) }}</div><div class="ml-stat-label">Tidak Aktif</div></div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-folder"></i></div><div><div class="ml-stat-value">{{ number_format($stats['categories']) }}</div><div class="ml-stat-label">Kategori</div></div></div></div></div></div>
    </div>

    {{-- Toolbar --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form action="{{ route('documents.index') }}" method="GET" class="d-flex flex-wrap align-items-end gap-2">
                    <div>
                        <label class="form-label small mb-0">Cari</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Dokumen..." value="{{ request('search') }}" style="min-width:180px;">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Kategori</label>
                        <select name="category_uuid" class="form-select form-select-sm" style="min-width:160px;">
                            <option value="">-- Semua --</option>
                            @foreach($categories as $c)<option value="{{ $c->uuid }}" @selected(request('category_uuid')==$c->uuid)>{{ $c->parent ? $c->parent->name . ' / ' : '' }}{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-0">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- Semua --</option>
                            <option value="active" @selected(request('status')=='active')>Aktif</option>
                            <option value="inactive" @selected(request('status')=='inactive')>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search','category_uuid','status']))<a href="{{ route('documents.index') }}" class="btn btn-sm btn-light">Reset</a>@endif
                    </div>
                </form>
                <div class="d-flex gap-2 align-items-center">
                    @can('documents.destroy')
                    <button type="button" id="bulkDeleteDocBtn" class="btn btn-sm btn-danger d-none" onclick="bulkDeleteDoc()"><i class="bi bi-trash"></i> Hapus terpilih (<span id="bulkCountDoc">0</span>)</button>
                    @endcan
                    @can('documents.create')
                    <a href="{{ route('documents.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <form id="bulk-delete-doc-form" action="{{ route('documents.bulkDestroy') }}" method="POST" class="d-none">
        @csrf @method('DELETE')
        <div id="bulk-ids-doc"></div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="small text-muted">{{ number_format($documents->total()) }} dokumen • Halaman {{ $documents->currentPage() }} dari {{ $documents->lastPage() }}</div>
        @can('documents.destroy')
        <label class="small d-flex align-items-center gap-1 mb-0"><input type="checkbox" id="checkAllDoc" class="form-check-input"> Pilih semua</label>
        @endcan
    </div>

    <div class="row g-3">
        @forelse($documents as $doc)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card border h-100 shadow-sm hover-lift">
                    <div class="position-relative">
                        @can('documents.destroy')
                        <div class="position-absolute top-0 start-0 m-2"><input type="checkbox" class="form-check-input doc-check" value="{{ $doc->uuid }}" style="width:18px;height:18px;"></div>
                        @endcan
                        @if($doc->thumbnail)
                            <img src="{{ asset('storage/'.$doc->thumbnail) }}" alt="{{ $doc->title }}" class="w-100" style="height:160px; object-fit:cover; border-top-left-radius:0.5rem; border-top-right-radius:0.5rem;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:160px; border-top-left-radius:0.5rem; border-top-right-radius:0.5rem;"><i class="bi bi-file-earmark-text text-secondary" style="font-size:2.2rem;"></i></div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($doc->status==='active')<span class="badge bg-success-subtle text-success border">Aktif</span>@else<span class="badge bg-secondary-subtle text-secondary border">Tidak Aktif</span>@endif
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        @if($doc->category)
                            @if($doc->category->parent)
                                <span class="badge bg-primary-subtle text-primary mb-1" style="font-size:0.7rem;">{{ $doc->category->parent->name }} / {{ $doc->category->name }}</span>
                            @else
                                <span class="badge bg-primary-subtle text-primary mb-1" style="font-size:0.7rem;">{{ $doc->category->name }}</span>
                            @endif
                        @endif
                        <h6 class="fw-bold mb-1" style="font-size:0.95rem; line-height:1.3;">{{ Str::limit($doc->title, 60) }}</h6>
                        @if($doc->excerpt)<p class="text-muted small mb-2" style="font-size:0.8rem;">{{ Str::limit($doc->excerpt, 90) }}</p>@endif
                        @if($doc->published_at)<small class="text-muted d-flex align-items-center gap-1"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($doc->published_at)->format('d M Y') }}</small>@endif
                    </div>
                    <div class="card-footer bg-transparent border-0 px-3 pb-3 pt-0">
                        <div class="d-flex gap-1">
                            @if($doc->file || $doc->file_url)<a href="{{ $doc->file_url ?: asset('storage/'.$doc->file) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Lihat</a>@endif
                            @can('documents.update')<a href="{{ route('documents.edit', $doc->uuid) }}" class="btn btn-sm btn-outline-dark flex-fill"><i class="bi bi-pencil"></i></a>@endcan
                            @can('documents.destroy')
                            <form action="{{ route('documents.destroy', $doc->uuid) }}" method="POST" class="flex-fill m-0" id="delDoc{{ $doc->uuid }}">@csrf @method('DELETE')<button type="button" onclick="delDoc('{{ $doc->uuid }}')" class="btn btn-sm btn-danger w-100"><i class="bi bi-trash"></i></button></form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="card border"><div class="card-body text-center py-5"><i class="bi bi-file-earmark-text fs-1 text-secondary"></i><h6 class="mt-2">Belum Ada Dokumen</h6><p class="text-muted small">Tambahkan dokumen pertama Anda.</p>@can('documents.create')<a href="{{ route('documents.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>@endcan</div></div></div>
        @endforelse
    </div>

    @if($documents->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <small class="text-muted">Menampilkan {{ $documents->firstItem() }}-{{ $documents->lastItem() }} dari {{ $documents->total() }}</small>
            <div>{{ $documents->links('pagination::minimal') }}</div>
        </div>
    @endif
</section>

@push('after-script')
<script>
function delDoc(uuid){ Swal.fire({title:'Hapus dokumen?', text:'Data akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r=>{ if(r.isConfirmed) document.getElementById('delDoc'+uuid).submit(); }); }
document.addEventListener('DOMContentLoaded', function(){
    const checkAll=document.getElementById('checkAllDoc'), btn=document.getElementById('bulkDeleteDocBtn'), cnt=document.getElementById('bulkCountDoc');
    function update(){ const checks=document.querySelectorAll('.doc-check'), sel=document.querySelectorAll('.doc-check:checked'); const n=sel.length; if(cnt) cnt.textContent=n; if(btn) btn.classList.toggle('d-none', n===0); if(checkAll){ checkAll.checked=n>0 && n===checks.length; checkAll.indeterminate=n>0 && n<checks.length; } }
    if(checkAll) checkAll.addEventListener('change', function(){ document.querySelectorAll('.doc-check').forEach(c=>c.checked=this.checked); update(); });
    document.addEventListener('change', e=>{ if(e.target.classList.contains('doc-check')) update(); });
    window.bulkDeleteDoc=function(){
        const ids=Array.from(document.querySelectorAll('.doc-check:checked')).map(c=>c.value); if(!ids.length) return;
        Swal.fire({title:'Hapus '+ids.length+' dokumen?', text:'Data terpilih akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r=>{ if(r.isConfirmed){ const f=document.getElementById('bulk-delete-doc-form'), c=document.getElementById('bulk-ids-doc'); c.innerHTML=''; ids.forEach(id=>{ const i=document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; c.appendChild(i); }); f.submit(); }});
    };
});
</script>
@endpush
@endsection
