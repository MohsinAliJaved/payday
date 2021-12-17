@extends('layout.app-candidate')

@section('title', trans('default.career_page'))

@section('sharable-content')
    <meta property="og:url" content="{{request()->root().'public/career'}}">
    <meta property="og:title" content="{{config('app.name')}}">
    <meta property="og:description" content="{{config('app.name').' - '.__t('job_point_description')}}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{request()->root().config('settings.application.company_logo')}}">
    <meta property="og:image:width" content="1000">
    <meta property="og:image:height" content="500">
@endsection

@section('contents')
    <candidate-career-page
            :career-page="{{json_encode($careerPage)}}"
            :job-posts="{{json_encode($jobPosts)}}">
    </candidate-career-page>
@endsection

