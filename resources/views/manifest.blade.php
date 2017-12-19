@extends('finnegan::admin.layouts.default')

@section('page-title', $title . ' - ')
@section('body-class', 'finn-api-manifest')

@section('inner-title')
    {!! $icons($icon) . ' ' . $title !!}
@stop

@section('model-actions')
    <a class="button warning" href="{{ route ('admin.api-manifest-export') }}">
        <i class="fa fa-download"></i> Export RAML</a>
@stop

@section('content')
    @if (count($endpoints))
        <div class="box is-active" id="{{$id = uniqid()}}" data-toggler=".is-active">
            <a class="title" data-toggle="{{$id}}">Endpoints</a>
            <div class="content">
                @foreach($endpoints as $endpoint)
                    @include("finnegan-api::{$endpoint->view()}")
                @endforeach
            </div>
        </div>
    @endif
@endsection