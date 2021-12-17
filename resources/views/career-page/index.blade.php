@extends('layout.tenant')

@section('title', trans('default.career_page'))

@section('contents')
    <career-page
            :career-page="{{json_encode($careerPage)}}"
            :job-posts="{{json_encode($jobPosts)}}">
    </career-page>
@endsection

