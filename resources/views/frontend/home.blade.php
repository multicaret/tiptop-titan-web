{{--@extends('layouts.frontend')--}}
{{--@section('title',__('Home'))--}}
{{--@section('content')--}}
    @include('frontend.'. localization()->getCurrentLocale().'-home')
{{--@endsection--}}
