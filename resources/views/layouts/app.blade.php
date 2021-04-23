@extends('layouts.master')

@section('body')
  <div id="app" class="flex flex-col min-h-screen">
    @section('header')
      @include('partials.header')
    @show
    <div class="flex-1">
      @yield('content')
    </div>
    @section('footer')
      @include('partials.footer')
    @show
  </div>
@endsection
