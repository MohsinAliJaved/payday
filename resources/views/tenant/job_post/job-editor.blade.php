@extends('layout.tenant')

@section('title', trans('default.job_post'))

@section('contents')
    <job-editor :job="{{json_encode($job)}}" :preview-link="{{json_encode($previewLink)}}"></job-editor>
@endsection

