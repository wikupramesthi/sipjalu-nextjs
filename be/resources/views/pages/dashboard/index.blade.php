@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Dashboard" page="Dashboard" active="" route="{{ route('dashboard.index') }}" />
@endsection

<div class="page-content">
    <section class="row">
        <div class="col-12">

            @role('user')
            @include('pages.dashboard.member')
            @else
            @include('pages.dashboard.card')
            @endif

        </div>

    </section>
</div>

@if ($showDefaultPasswordModal || $errors->getBag('updatePassword')->any())
    @include('profile.partials.change-password', ['showOnLoad' => $showDefaultPasswordModal])
@endif

@endsection