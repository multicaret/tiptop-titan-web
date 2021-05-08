@extends('layouts.error')

@section('title', trans('strings.page-expired'))

@section('content')
    <div class="margin-bottom-80 margin-top-80">
        <h3 class="text-center">
            @lang('strings.the-page-has-expired')
            <br/><br/>
            @lang('strings.please-refresh')
            <br/><br/>
            <a href="/login">@lang('strings.login')</a> - <a href="/register">@lang('strings.sign up')</a>
        </h3>
    </div>
@stop
