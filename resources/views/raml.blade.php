#%RAML 0.8
---
title: {{ config('app.name') }} API
version: v1
baseUri: https://{{ url('/api/') }}/
mediaType: application/json
@foreach($api->getEndpoints() as $endpoint)

@endforeach