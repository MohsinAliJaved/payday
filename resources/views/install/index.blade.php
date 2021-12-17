@extends('install.layout')

@section('title', trans('default.install'))

@section('contents')
    <app-install-wizard
        errors="{{ isset($validation) ? $validation : '' }}"
        app-name="{{ config('app.name') }}">
    </app-install-wizard>
@endsection
