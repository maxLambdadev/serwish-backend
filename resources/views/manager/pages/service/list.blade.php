@extends('manager.layouts.app')

@section('panel')
    @include('manager.partials.actionPanel',[
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.services.service.bulk.delete',
        'customEditName'=> 'დეტალურად',
        'customEditIcon'=> 'fa-eye',
        'customButtons'=> [
            ['route'=> 'manager.services.service.export', 'name'=> 'ექსპორტი','icon'=> 'fa-file-export', 'triggerClass'=>'export-btn']
        ]
    ])
@endsection

@push('crumbs')
    <li class="breadcrumb-item">სერვისები</li>
    <li class="breadcrumb-item active">მომსახურებები</li>
@endpush
@section('section-title', 'სერვისები')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">სერვისები</h3>
                <div class="card-tools " style="width: 50%">
                    <form action="" method="get" class="row">

                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" name="daterange" id="reservationtime">
                            </div>
                        </div>

                        <div class="col-2">
                            <a href="{{route('manager.services.service.index')}}">
                                <span style="font-size: 18px;">
                                     <i class="fas fa-times"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-6" style="padding-top:1%; visibility:hidden">
                            <div class="input-group input-group-md div " style="width: 150px;">
                                <div class="input-group-append">
                                    {{--                                    <button type="submit" class="btn btn-default filter-form">--}}
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>


                </div>

            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                @if(!$list->isEmpty())
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="bulkCheck" >
                                        <label for="bulkCheck"></label>
                                    </div>
                                </div>
                            </th>
                            {{--                            <th>სერვისი</th>--}}
                            <th>სტატუსი</th>
                            <th>შემსრულებელი</th>
                            <th>ტელეფონი</th>
                            <th>S ხარისხი</th>
                            <th>სტატუსი</th>
                            <th>category</th>
                            <th>
                                დარეკა
                                <form action="{{route('manager.services.service.index')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="filter" value="calls_count">
                                    <input type="hidden" name="page" value="{{$list->currentPage()}}">
                                    <input id="call-order-id" type="hidden" name="order">
                                    <button id="filter-call" type="submit"></button>
                                </form>
                            </th>
                            <th>
                                ნახვები
                                <form action="{{route('manager.services.service.index')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="filter" value="views_count">
                                    <input type="hidden" name="page" value="{{$list->currentPage()}}">
                                    <input id="seen-order-id" type="hidden" name="order">
                                    <button id="filter-seen" type="submit"></button>
                                </form>
                            </th>
                            <th>მოქმედება</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif {!!  request()->get('edited') == $l->id ? 'style="background-color:#acacac;"' : '' !!} >
                                <td>
                                    <div class="col-sm-6 d-flex justify-content-between">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="item-{{$l->id}}" class="check" value="{{$l->id}}" >
                                                <label for="item-{{$l->id}}">
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                        $slug = \App\Models\Blog\CategoryTranslation::where('category_id','=',$l->category[0])
                                            ->where('locale', App::getLocale())
                                            ->first()
                                            ->slug;
                                        ?>
                                        <a href="https://serwish.ge/services/details/{{$l->id}}/{{$slug}}" target="_blank" class="idBordered">
                                            ID: 00{{$l->id}}
                                        </a>
                                    </div>
                                </td>
                                {{--                                <td>{{\Illuminate\Support\Str::limit($l->title,10)}}</td>--}}
                                <td class="ltl_font">
                                    {!!\App\Helpers\Beautifier::getStatus($l)!!}
                                </td>
                                <td>
                                    <span class="ltl_font names_td">
                                          <i>
                                                @if ($l->service_name)
                                                  {{$l->service_name}}
                                              @else
                                                  {{$l->specialist->name}}
                                              @endif
                                          </i>
                                        <i>
                                            /{{$l->specialist->name}}
                                        </i>

                                        <img src="{{asset('/themes/martve/img/edit.png')}}" data-category="{{$l->id}}" alt="Edit button" class="edit-btn">
                                    </span>
                                </td>
                                <td>
                                    @if($l->contact_number == null || $l->contact_number == "")
                                        {{$l->specialist->phone_number}}
                                    @else
                                        {{$l->contact_number}}
                                    @endif
                                    <br>
                                    <span class="ltl_font" style="color:black; font-weight: bold">
                                        {{\Carbon\Carbon::parse($l->created_at)->isoFormat('dddd D MMMM')}}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch{{$l->id}}" {!!$l->has_serwish_quality ? 'checked="checked"' : ''  !!} disabled>
                                            <label class="custom-control-label" for="customSwitch{{$l->id}}"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch{{$l->id}}" {!!$l->is_active ? 'checked="checked"' : ''  !!} disabled>
                                            <label class="custom-control-label" for="customSwitch{{$l->id}}"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                   <span class="ltl_font names_td">
                                       <i>
                                            {!!
                                                \App\Models\Blog\CategoryTranslation::where('category_id', '=', $parent_cats[$l->category[0]][0])
                                                ->where('locale', App::getLocale())
                                                ->first()
                                                ->title
                                            !!}
                                       </i>
                                        <i>
                                            {!!
                                                \App\Models\Blog\CategoryTranslation::where('category_id', '=', $l->category[0])
                                                ->where('locale', App::getLocale())
                                                ->first()
                                                ->title
                                            !!}
                                        </i>
                                   </span>
                                </td>
                                <td style="color: red">
                                   <span class="ltl_font">
                                        @if (isset($l->calls_count) && !is_null($l->calls_count))
                                           {{$l->calls_count}}
                                       @else
                                           0
                                       @endif
                                   </span>
                                </td>
                                <td style="color: red">
                                    <span class="ltl_font">
                                        @if (isset($l->views_count) && !is_null($l->views_count))
                                            {{$l->views_count}}
                                        @else
                                            0
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if(Illuminate\Support\Facades\Auth::user()->hasRole('administrator') || request()->get('review_type') == 'started')
                                        @include('manager.partials.actionButton', [
                                            'permission' => 'service',
                                            'editRoute' => route('manager.services.service.show', ['id'=>$l->id]),
                                            'customEditName' => 'დეტალურად',
                                            'deleteRoute' => route('manager.services.service.destroy', ['id'=>$l->id]),
                                        ])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                        სერვისები არ არის
                    </div>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            @if(!request()->get('review_type'))
                {!! $list->links() !!}
            @endif
        </div>
        <!-- /.card -->
    </div>
@endsection
@push('footer')
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-light">
        <!-- Control sidebar content goes here -->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="category-list d-flex justify-content-between flex-column">
                        @foreach($categories as $cat)
                            <div class="category-item">
                                <span class="category-arrow" style="visibility: hidden">
                                    <img src="{{asset('arrow.svg')}}" width="10" height="10">
                                </span>
                                <div>
                                    <span>{{$cat->title}}</span>
                                    @php($img = $cat->images()->first())
                                    @if($img != null)
                                        <img src="{{asset('storage/'.$img->path)}}" alt="" width="24" height="24">
                                    @endif
                                </div>
                            </div>
                            <div class="hidden sub-cats" style="display: none">
                                @foreach($cat->childrens as $childCat)
                                    <a href="{{route('manager.dashboard',[
                                        'category'=>$childCat->id,
                                        'review_status' => request()->get('review_status'),
                                        'title' => request()->get('title')
                                        ])}}"
                                       class="category-item justify-content-end child-category"
                                       data-id="{{$childCat->id}}"
                                    >
                                        <div>
                                            <span>{{$childCat->title}}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <div class="edit-name-modal">
        <img src="{{asset('/themes/martve/img/close.png')}}" alt="Close button" class="close-btn">
        <form action="{{route('manager.services.service.update-service-name')}}" method="post">
            @csrf
            <input id="service-id" type="hidden" name="service_id">
            <input type="text" class="edit-name" placeholder="Enter new name" name="name">
            <button type="submit">Save</button>
        </form>
    </div>
    <div class="sub-cat-overlay" style="display: none">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="sub-category-append" class="category-list d-flex justify-content-between flex-column">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.control-sidebar -->

    <style>
        .names_td {
            display: flex;
            flex-direction: column;
        }
        .names_td i{
          font-style: normal;
        }
        .table thead th {
            font-size: 11px;
        }
        .idBordered {
            border:1px #000000 dashed;
            padding: 0px 5px;
            border-radius: 5px;
            color:#000000;
            font-size: 12px;
            margin-bottom: auto;
        }
        .edit-name-modal {
            position: fixed;
            width: 300px;
            height: auto;
            background-color: rgba(0, 0, 0, .3);
            top: 30%;
            left: calc(50% - 150px);
            z-index: 1000;
            padding: 10px;
            display: none;
        }

        .close-btn {
            position: absolute;
            top: 0px;
            right: 0px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .edit-btn {
            position: absolute;
            right: 0;
            top: 15px;
            width: 15px;
            height: 10px;
            cursor: pointer;
        }

        th,
        td {
            position: relative;
        }

        #filter-call,
        #filter-seen {
            cursor: pointer;
            position: absolute;
            border: none;
            background-color: transparent;
            right: -5px;
            top: 20px;
            width: 7px;
            height: 13px;
            border-radius: 50%;
            border: 1px solid green;
            transition: .3s;
            outline: none;
        }

        .category-list{
            padding:10px;
            color:rgba(63, 70, 85, 0.4);
        }

        .category-item{
            text-align: right;
            font-weight: bold;
            padding:10px;
            font-size:14px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .category-item.category-arrow{
            float: left;
        }

        .category-item:not(:last-child){
            border-bottom: 1px solid #eff2f6;
        }

        .category-item:hover{
            cursor: pointer;
            color:#3f4655;
        }

        .child-category{
            color:#3f4655;
        }

        .active-color{
            color:#3f4655;
        }

        .sub-cat-overlay{
            background-color: #eff2f6;
            position: absolute;
            top: 0;
            right: 32%;
            z-index: 99;
            width: 34%;
            height: 100%;

        }
        .control-sidebar{
            width:32% !important;
        }
        .ltl_font {
            font-size: 10px;
            padding-right: 10px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            if (localStorage.getItem('call') === "true") {
                $('#filter-call').css('background-color', 'transparent');
            } else {
                $('#filter-call').css('background-color', 'green');
            }

            if (localStorage.getItem('seen') === 'true') {
                $('#filter-seen').css('background-color', 'transparent');
            } else {
                $('#filter-seen').css('background-color', 'green');
            }

            $('.edit-btn').on('click', function() {
                $('.edit-name-modal').css('display', 'block');
                $('#service-id')[0].value = $(this)[0].dataset.category;
            });

            $('.close-btn').on('click', function() {
                $('.edit-name-modal').css('display', 'none');
                $('#service-id')[0].value = "";
            });

            $('#filter-call').on('click', function() {
                if (localStorage.getItem('call') === "true") {
                    localStorage.setItem('call', false);
                    $('#call-order-id')[0].value = "DESC";
                    $(this).css('background-color', 'green');
                } else {
                    localStorage.setItem('call', true);
                    $('#call-order-id')[0].value = "ASC";
                    $(this).css('background-color', 'transparent');
                }
            });

            $('#filter-seen').on('click', function() {
                if (localStorage.getItem('seen') === 'true') {
                    localStorage.setItem('seen', false);
                    $('#seen-order-id')[0].value = "DESC";
                    $(this).css('background-color', 'transparent');
                } else {
                    localStorage.setItem('seen', true);
                    $('#seen-order-id')[0].value = "ASC";
                    $(this).css('background-color', 'green');
                }
            });

            $('#reservationtime').daterangepicker({
                showCustomRangeLabel:false,
                timePicker: true,
                alwaysShowCalendars: true,
                timePicker24Hour: true,
                ranges   : {
                    'დღეს'       : [moment(), moment()],
                    'გუშინ'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'ბოლო 7 დღე' : [moment().subtract(6, 'days'), moment()],
                    'ბოლო 30 დღე': [moment().subtract(29, 'days'), moment()],
                    'მიმდინარე თვე'  : [moment().startOf('month'), moment().endOf('month')],
                    'გასული თვე'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                @if(request()->get('daterange'))
                <?php
                    $date = str_replace('+',' ', request()->get('daterange'));
                    $dates = explode(' - ',$date);
                    $startDate = Carbon\Carbon::parse($dates[0])->format('m/d/Y');
                    $endDate = Carbon\Carbon::parse($dates[1])->format('m/d/Y');
                    ?>
                startDate: moment('{{$startDate}}'),
                endDate  : moment('{{$endDate}}')
                @else
                startDate: moment().startOf('day'),
                endDate  : moment().endOf('day')
                @endif
            })

            $('#reservationtime').on('apply.daterangepicker',function (){
                $('.filter-form').trigger('click')
            })


            $('.category-item').hover(  function() {
                $(this).children('span.category-arrow').css("visibility", "visible")
            }, function() {
                $(this).children('span.category-arrow').css("visibility", "hidden")
            })


            $('.category-item').click(function (){
                let subCatOverlay = $(".sub-cat-overlay")

                subCatOverlay.hide(10)
                subCatOverlay.show(500)

                $('.category-item').removeClass('active-color')

                $(this).addClass('active-color')

                $('#sub-category-append').html(
                    $(this).next('.sub-cats').html()
                )
                console.log($(this).next('.sub-cats').html())
            })


            $('.select2').select2({
                theme: 'bootstrap4'
            })

            $('.export-btn').click(function(){
                let exportIds = countChecked()
                $(this).attr('href',`{{route('manager.services.service.export')}}?exportIds[]=${exportIds.join('&exportIds[]=')}`)
                $(this).click()
            })

            $('.review-change').change(function (){
                $('.filter-form').trigger('click')
            })
        });

        function countChecked(){
            let selector = '.check'
            let ids = [];
            $(selector).each(function(){
                if ($(this).is(':checked'))
                {
                    ids.push($(this).val())
                }
            });
            return ids;
        }
    </script>
@endpush