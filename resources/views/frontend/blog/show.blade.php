@extends('layouts.frontend')
@section('title', $post->title)
@section('content')
    {!! $post->content !!}
@endsection
