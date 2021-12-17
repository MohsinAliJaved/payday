@extends('layout.tenant')

@section('title', trans('default.job_settings'))

@section('contents')
    <job-settings :job-post-id="{{$job_post_id}}"></job-settings>
@endsection
