@extends('layouts.app')
@section('title', 'Tambah Agenda')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Tambah Agenda" page="Agenda" active="Tambah Agenda" route="{{ route('agendas.index') }}" />
@endsection

<section class="section">
    @include('pages.agendas.partials.form', [
        'action' => route('agendas.store'),
        'method' => 'POST',
        'agenda' => null,
        'submitLabel' => 'Simpan Agenda',
    ])
</section>

@endsection
