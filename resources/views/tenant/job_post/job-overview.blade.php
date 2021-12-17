@extends('layout.tenant')

@section('title', __t('job_post'))

@section('contents')
    <job-overview :job-post-id="{{$job_post_id}}"></job-overview>
@endsection
