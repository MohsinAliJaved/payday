@extends('install.layout')

@section('title', trans('default.install'))

@section('contents')
    <div class="root-preloader position-absolute overlay-loader-wrapper">
        <div class="spinner-bounce d-flex align-items-center justify-content-center h-100">
            <span class="bounce1 mr-1"></span>
            <span class="bounce2 mr-1"></span>
            <span class="bounce3 mr-1"></span>
            <span class="bounce4"></span>
        </div>
    </div>

    <app-environment-wizard app-name="{{ config('app.name') }}"></app-environment-wizard>
@endsection

<script>
    window.addEventListener('load', function() {
        document.querySelector('.root-preloader').remove();
    });
</script>
