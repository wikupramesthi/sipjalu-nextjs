@extends('layouts.app')
@section('title', 'Edit Agenda')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Edit Agenda" page="Agenda" active="Edit Agenda" route="{{ route('agendas.index') }}" />
@endsection

<section class="section">
    @include('pages.agendas.partials.form', [
        'action' => route('agendas.update', $agenda->uuid),
        'method' => 'PUT',
        'agenda' => $agenda,
        'submitLabel' => 'Perbarui Agenda',
    ])
</section>

@endsection
