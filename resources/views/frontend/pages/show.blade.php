@extends('layouts.frontend')
@section('title',$page->title)
@section('content')
    {{$page->title}}
    {!! $page->content !!}
@endsection
