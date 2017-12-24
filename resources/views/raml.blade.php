#%RAML 0.8
---
title: {{ config('app.name') }} API
version: v1
baseUri: {{ url('/api/') }}
mediaType: application/json
@foreach($api->getEndpoints() as $endpoint)
@if(!$endpoint instanceof \Finnegan\Api\Endpoints\ModelsEndpoint)
/{{ $endpoint->uri }}:
@if($endpoint->description)
  description: {{$endpoint->description}}
@endif
@foreach((array)$endpoint->get('methods', ['get']) as $method)
  {{ strtolower ($method) }}:
@endforeach
@else
/models:
  /{model}:
    get:
      description: Get the paginated list of models.
    /{id}:
      get:
        description: Get a model by ID.
@endif
@endforeach