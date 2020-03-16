@extends('errors.layout')

@php
  $error_number = 403;
@endphp

@section('title')
  Forbidden.
@endsection

@section('description')
  @php
    $default_error_message = "Please <a href='javascript:history.back()''>go back</a> or return to <a href='".url('')."'>our homepage</a>.";
  @endphp
  <div class="">
    {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}

  </div>
  <div class="tw-mt-4">
    <a href="{{ backpack_url('logout') }}">Logout</a>
  </div>

@endsection
