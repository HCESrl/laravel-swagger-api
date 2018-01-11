@extends('finnegan::admin.layouts.default')

@section('body-class', 'finn-api-docs')

@section('page-title', 'API Docs - ')

@section('inner-title')
    {!! $icons('plug') !!} API Docs
@stop

@section('model-actions')
    <a href="{{ route ('finnegan-api.docs') }}" target="_blank"
       class="button">Open in new window {!! $icons('external-link') !!}</a>
@stop

@section('content')
    <iframe src="{{ route ('finnegan-api.docs') }}"
            style="width: 100%; height: calc(100vh - 93px);border:none;"></iframe>
@stop
