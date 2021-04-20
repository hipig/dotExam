@extends('layouts.master')

@section('body')
  <div id="app" class="flex flex-col min-h-screen">
    @include('partials.header')
    <div class="flex-1">
      @yield('content')
    </div>
    @include('partials.footer')
  </div>
@endsection
