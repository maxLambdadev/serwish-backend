
<span class="input-holders">
@if($entity != null)
        @foreach($entity->images as $image)
            <input type="hidden" name="media{!! $multiple ? '[]' : '' !!}" value="{{$image->id}}" class="media-{{$image->id}}">
        @endforeach
@endif
</span>

<label for="dropzone">{{ __('panel.mediaManager')  }}</label>
<div id="actions" class="row">
    <div class="col-lg-12">
        <div class="btn-group w-100">
            <button type="button" class="btn btn-success col fileinput-button dropzone-fileinput-button"  data-multiple="{{intval($multiple)}}">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-primary col start">
                <i class="fas fa-upload"></i>
            </button>
            <button type="reset" class="btn btn-warning col cancel">
                <i class="fas fa-times-circle"></i>
            </button>
            <button type="button" class="btn btn-info col browse-media" >
                <i class="fab fa-searchengin"></i>
            </button>
        </div>
    </div>

    <div class="col-lg-12 d-flex align-items-center" style="margin-top:3%">
        <div class="fileupload-process w-100">
            <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
            </div>
        </div>
    </div>
</div>


<div class="image-holders">
    <div class="table table-striped files " id="previews">
        <div id="template" class="row mt-2">
            <div class="col-auto each-media-element">
                <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
            </div>
            <div class="col d-flex ">
                <p class="mb-0 stretch">
                    <span class="lead small-text" data-dz-name></span>
                </p>
                <strong class="error text-danger" data-dz-errormessage></strong>
            </div>
            <div class="col-auto d-flex align-items-center">
                <div class="btn-group">
                    <button class="btn btn-primary start" type="button">
                        <i class="fas fa-upload"></i>
                    </button>
                    <button  class="btn btn-danger delete" type="button" >
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
    @if($entity != null)
        @foreach($entity->images as $image)
            <div class="table table-striped files" id="previews">
                <div id="template" class="row mt-2">
                    <div class="col-auto each-media-element">
                        <span class="preview"><img src="{{asset('/storage/'.$image->path)}}" alt="" data-dz-thumbnail width="80" height="80"/></span>
                    </div>
                    <div class="col d-flex align-items-start">
                        <p class="mb-0 stretch">
                            <span class="lead small-text" data-dz-name>{{$image->name}}</span>
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </div>

                    <div class="col-auto d-flex align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-primary start" type="button" disabled>
                                <i class="fas fa-upload"></i>
                            </button>
                            <button class="btn btn-info makeDefault" type="button"
                                    data-id="{{$image->id}}"
                                    data-route="{{url('/panel/media/make-default')}}"
                                    data-other-entity="{{$removeEntity}}"
                                    @if ($entity != null)
                                        data-other-id="{{$entity->id}}"
                                    @endif
                                    data-enable="{{$image->pivot->is_active == 0 ? 1 : 0}}"
                                @if ($image->pivot->is_active == true)
                                        title="მთავარი ფოტოს გაუქმება"
                                @else
                                        title="გახადე მთავარი ფოტო"
                                @endif
                            >
                                @if ($image->pivot->is_active == true)
                                    <i class="fas fa-image"></i>
                                @else
                                    <i class="fas fa-images"></i>
                                @endif
                            </button>

                            <button class="btn btn-danger delete" type="button" data-id="{{$image->id}}"
                                    data-route="{{$removeRoute}}"
                                    data-other-entity="{{$removeEntity}}"
                                    @if ($entity != null)
                                        data-other-id="{{$entity->id}}"
                                    @endif
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>


<div class="modal fade" id="media-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">მედიის მენეჯერი</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body scrollable"></div>

            <div class=" modal-sidebar">
                <div class="d-flex">
                    <div class="p-12 close-media">
                       <span class="close-media-edit"> &times;</span>
                    </div>
                    <div class="p-2">
                        <img src='holder.jpg' width="120" height="120" class="image-path-holder" />
                    </div>
                    <div class=" p-2">
                        <div>
                            <div class="form-group">
                                <label for="image-name">სახელი</label>
                                <input type="text" name="imageName" class="form-control image-name">
                            </div>
                            <button type="button" class="btn btn-success update-image-button">შენახვა</button>
                            <button type="button" class="btn btn-danger delete-image-button">წაშლა</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@push('script')
    <script src="{{asset('manager/js/dropzone.js')}}"></script>
    <script src="{{asset('manager/js/modal.js')}}"></script>


@endpush
