<div class="callout success">
    <p class="clearfix">
        <a href="{{ route('api.index', $modelNameCallback(array_first ($endpoint->models))) }}" target="_blank"
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
        <h6><u>Available models:</u></h6>
        <ul>
            @foreach($endpoint->models as $class)
                @php($model = $modelNameCallback($class))
                <li><a href="{{ route('api.index', $model) }}" target="_blank">{{ $model }}</a></li>
            @endforeach
        </ul>
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
        <a href="{{ route('api.show', ['model'=>$modelNameCallback(array_first ($endpoint->models)), 'id'=>':id']) }}" target="_blank"
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
        <h6><u>Available models:</u></h6>
        <ul>
            @foreach($endpoint->models as $class)
                @php($model = $modelNameCallback($class))
                <li><a href="{{ route('api.show', ['model'=>$model, 'id'=>':id']) }}" target="_blank">{{ $model }}</a></li>
            @endforeach
        </ul>
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