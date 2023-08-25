
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($panelTitle) ? $panelTitle : 'Action Panel' }}</h3>
        </div>
        <div class="card-body">

            <a class="btn btn-app" href="{{route($createRoute)}}">
                <i class="fas fa-play"></i> Add
            </a>

            <a class="btn btn-app" href="#" >
                <i class="fas fa-edit"></i> Edit
            </a>

            <a class="btn btn-app"
               href="#"
               id="{{isset($bulkDelete) ? 'bulkDelete': ''}}"
               data-route="{{isset($bulkDeleteRoute) ? route($bulkDeleteRoute): ''}}">
                <i class="fas fa-trash-alt"></i> Delete
            </a>
        </div>
    </div>
</div>

