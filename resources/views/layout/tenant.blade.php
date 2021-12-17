@extends('common.master')

@section('master')
    {{-- @php
        var_dump($logo);
        die();
    @endphp --}}
    <div class="container-scroller">
        @section('top-bar')
            <app-top-navigation-bar logo-icon-src="{{ $logo_icon }}"
                                    :profile-data="{{ json_encode($top_bar_menu) }}"
                                    :has-work-shift="{{ $hasDefaultWorkShift ? 'true' : 'false' }}">
            </app-top-navigation-bar>
        @show

        @section('side-bar')
            <sidebar :data="{{ json_encode($permissions)  }}"
                     logo-src="{{ $logo }}"
                     logo-icon-src="{{ $logo_icon }}"
                     logo-url="{{ request()->root()  }}">
            </sidebar>
        @show
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                @yield('contents')
            </div>
        </div>
    </div>
@endsection

@push('before-scripts')
    {{-- <script>
        window.tenant = {!! json_encode(tenant()) !!}
    </script> --}}
    <script>
        window.settings = {!! json_encode($settings) !!}
    </script>
    <script>
        window.user = {!! auth()->user()->load('profilePicture', 'roles:id,name') !!}
    </script>
@endpush
