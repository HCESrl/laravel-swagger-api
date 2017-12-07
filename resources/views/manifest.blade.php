@extends('finnegan::admin.layouts.default')

@section('page-title', $title . ' - ')
@section('body-class', 'finn-api-manifest')

@section('inner-title')
    {!! $icons($icon) . ' ' . $title !!}
@stop

@section('content')
	<?php $methodMap = [ 'GET' => 'success', 'HEAD' => 'secondary', 'POST' => 'primary', 'PUT' => 'warning', 'PATCH' => 'secondary', 'DELETE' => 'alert' ]; ?>
    @if (count($models))
        <div class="box is-active" id="{{$id = uniqid()}}" data-toggler=".is-active">
            <a class="title" data-toggle="{{$id}}">Model Endpoints</a>
            <div class="content">
                <div>
                    <h5>Available models:</h5>
                    <div class="callout primary">
                        <ul>
                            @foreach($models as $class)
                                @php($model = $modelNameCallback($class))
                                <li><a href="{{ route('api.index', $model) }}" target="_blank">{{ $model }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div>
                    <h5>Endpoints:</h5>
                    <div class="callout success">
                        <p class="clearfix">
                            <a href="{{ route('api.index', ':model') }}" target="_blank"
                               class="float-right">{!! $icons('external-link') !!}</a>
                            <code>/api/models/<span style="color:orange;">{model}</span></code>
                            <span class="label success">GET</span>
                        </p>
                        <hr>
                        <div>
                            <h6><u>Description:</u></h6>
                            <p>Get the paginated list of models.</p>
                        </div>
                        <div>
                            <h6><u>Parameters:</u></h6>
                            <table>
                                <tbody>
                                <tr>
                                    <td><code>all</code></td>
                                    <td>Boolean</td>
                                    <td>If present, forces skipping the pagination.</td>
                                </tr>
                                <tr>
                                    <td><code>page</code></td>
                                    <td>Numeric</td>
                                    <td>The page number.</td>
                                </tr>
                                <tr>
                                    <td><code>sort</code></td>
                                    <td>String</td>
                                    <td>Sort the results by the given field name.</td>
                                </tr>
                                <tr>
                                    <td><code>descasc</code></td>
                                    <td>[desc|asc]</td>
                                    <td>The sorting direction.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h6><u>Examples:</u></h6>
                            <table>
                                <tbody>
                                <tr>
                                    <td>Get the post lists, default behaviour</td>
                                    <td><code>{{ route('api.index', ['model'=>'posts']) }}</code></td>
                                </tr>
                                <tr>
                                    <td>Get all the posts, no pagination</td>
                                    <td><code>{{ route('api.index', ['model'=>'posts', 'all'=>true]) }}</code></td>
                                </tr>
                                <tr>
                                    <td>Get the second page of the list, sorting by the most recent record</td>
                                    <td>
                                        <code>{{ route('api.index', ['model'=>'posts', 'page'=>2, 'sort'=>'created_at', 'descasc'=>'desc']) }}</code>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="callout success">
                        <p class="clearfix">
                            <a href="{{ route('api.show', ['model'=>':model', 'id'=>':id']) }}" target="_blank"
                               class="float-right">{!! $icons('external-link') !!}</a>
                            <code>/api/models/<span style="color:orange;">{model}</span>/<span
                                        style="color:orange;">{id}</span></code>
                            <span class="label success">GET</span>
                        </p>
                        <hr>
                        <div>
                            <h6><u>Description:</u></h6>
                            <p>Get a model by ID.</p>
                        </div>
                        <div>
                            <h6><u>Examples:</u></h6>
                            <table>
                                <tbody>
                                <tr>
                                    <td>Load the post with id=2</td>
                                    <td><code>{{ route('api.show', ['model'=>'posts', 'id'=>2]) }}</code></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (count($endpoints))
        <div class="box is-active" id="{{$id = uniqid()}}" data-toggler=".is-active">
            <a class="title" data-toggle="{{$id}}">{{ count($models) ? 'Other' : '' }} Endpoints</a>
            <div class="content">
                @foreach($endpoints as $endpoint)
                    @php($firstMethod = strtoupper (array_first((array)$endpoint->methods)))
                    <div class="callout {{ array_get($methodMap, $firstMethod) }} }}">
                        <p class="clearfix">
                            @if($firstMethod == 'GET')
                                <a href="/api/{{ $endpoint->uri }}" target="_blank"
                                   class="float-right">{!! $icons('external-link') !!}</a>
                            @endif
                            <code>/api/{!! preg_replace('#({[^}]+})#', '<span style="color:orange;">$1</span>', $endpoint->uri) !!}</code>
                            @foreach((array)$endpoint->methods as $method)
                                <span class="label {{ array_get($methodMap, strtoupper ($method)) }}">{{ strtoupper ($method) }}</span>
                            @endforeach
                        </p>
                        <hr>
                        @if ($endpoint->description)
                            <div>
                                <h6><u>Description:</u></h6>
                                <p>{{ $endpoint->description }}</p>
                            </div>
                        @endif
                        @if ($endpoint->parameters and is_array ($endpoint->parameters) and count($endpoint->parameters))
                            <div>
                                <h6><u>Parameters:</u></h6>
                                <table>
                                    <tbody>
                                    @foreach ($endpoint->parameters as $parameter)
                                        <tr>
                                            <td><code>{{ $parameter[0] }}</code></td>
                                            <td>{{ $parameter[1] or '' }}</td>
                                            <td>{{ $parameter[2] or '' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if ($endpoint->examples and is_array ($endpoint->examples) and count($endpoint->examples))
                            <div>
                                <h6><u>Examples:</u></h6>
                                <table>
                                    <tbody>
                                    @foreach ($endpoint->examples as $example)
                                        <tr>
                                            <td>{{ $example[0] }}</td>
                                            <td><code>{{ $example[1] }}</code></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection