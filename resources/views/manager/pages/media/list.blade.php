@extends('manager.layouts.app')


@section('section-title',__('mediaManager'))

@push('crumbs')
    <li class="breadcrumb-item active">მედია მენეჯერი </li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card card-primary">
            <div class="card-body">
                <div>
{{--                    <div class="btn-group w-100 mb-2">--}}
{{--                        <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All items </a>--}}
{{--                        <a class="btn btn-info" href="javascript:void(0)" data-filter="1"> Category 1 (WHITE) </a>--}}
{{--                        <a class="btn btn-info" href="javascript:void(0)" data-filter="2"> Category 2 (BLACK) </a>--}}
{{--                        <a class="btn btn-info" href="javascript:void(0)" data-filter="3"> Category 3 (COLORED) </a>--}}
{{--                        <a class="btn btn-info" href="javascript:void(0)" data-filter="4"> Category 4 (COLORED, BLACK) </a>--}}
{{--                    </div>--}}
                    <div class="mb-2">
                        <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> არევა </a>
                        <div class="float-right">
                            <select class="custom-select" style="width: auto;" data-sortOrder>
                                <option value="index"> სორტირება პოზიციით </option>
                                <option value="sortData"> სორტირება </option>
                            </select>
                            <div class="btn-group">
                                <a class="btn btn-default" href="javascript:void(0)" data-sortAsc> ზრდადობით </a>
                                <a class="btn btn-default" href="javascript:void(0)" data-sortDesc> კლებადობით </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="filter-container p-0 row">
                        @foreach($list as $l)
                            <div class="filtr-item col-sm-2" data-category="1" data-sort="{{$l->id}}">
                                <a href="{{asset('/storage/'.$l->path)}}" data-toggle="lightbox" data-title="{{$l->name}}">
                                    <img src="{{asset('/storage/'.$l->path)}}" class="img-fluid mb-2" alt="{{$l->name}}" width="120" height="120"/>
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('manager/plugins/ekko-lightbox/ekko-lightbox.css')}}">
@endpush

@push('script')
    <script src="{{asset('manager/plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
    <script src="{{asset('manager/plugins/filterizr/jquery.filterizr.min.js')}}"></script>
<script>
    $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });

        $('.filter-container').filterizr({gutterPixels: 3});
        $('.btn[data-filter]').on('click', function() {
            $('.btn[data-filter]').removeClass('active');
            $(this).addClass('active');
        });
    })
</script>
@endpush
