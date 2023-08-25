@extends('manager.layouts.app')


@section('panel')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">გამოქვეყნებული სერვისები</h3>
            </div>

            <div class="card-body row">

                <div class="col-4">
                </div>
                <div class="row col-2">
                    <div class="col-4 dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="fas fa-envelope"></i>
                            <span class="badge badge-warning navbar-badge" style="margin-right: 10px;">{{\App\Models\ContactRequests::where('seen',false)->count()}}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">კონტაქტის მოთხოვნები</span>
                            @foreach($contactReqs as $contactReq)
                                <a href="{{route('manager.contact-requests.list', ['highlight'=>$contactReq->id])}}" class="dropdown-item">
                                    {{$contactReq->phone}} {{\Illuminate\Support\Str::limit($contactReq->title,10)}} {{\Illuminate\Support\Str::limit($contactReq->email,20)}}
                                </a>
                                <div class="dropdown-divider"></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-4 dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="fas fa-phone"></i>
                            <span class="badge badge-warning navbar-badge" style="margin-right: 10px;">{{\App\Models\CallRequests::where('is_called',false)->count()}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">ზარის მოთხოვნები</span>
                            @foreach($callReqs as $callReq)
                                <a href="{{route('manager.call-requests.list', ['highlight'=>$callReq->id])}}" class="dropdown-item">
                                    {{$callReq->phone_number}}  {!! $callReq->category != null ? $callReq->category->title : '' !!}
                                </a>
                                <div class="dropdown-divider"></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-4 dropdown">
                        <a class="nav-link"  href="{{ route('manager.services.service.index',['review_type'=>'started'])  }}">
                        <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge" style="margin-right: 10px;">{{ $newServices->count()}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
{{--                            <span class="dropdown-item dropdown-header">ახალი სერვისები</span>--}}
{{--                            @foreach($newServices as $newService)--}}
{{--                                <a href="{{ route('manager.services.service.show',['id'=>$newService->id])  }}" class="dropdown-item">--}}
{{--                                    {{\Illuminate\Support\Str::limit($newService->title,15)}} {{\Illuminate\Support\Str::limit($newService->specialist->name,15)}}--}}
{{--                                </a>--}}
{{--                                <div class="dropdown-divider"></div>--}}
{{--                            @endforeach--}}
{{--                            <div class="dropdown-divider"></div>--}}
{{--                            <a href="{{ route('manager.services.service.index',['review_type'=>'started'])  }}" class="dropdown-item" style="color: blue; font-style: italic">--}}
{{--                                ყველას ნახვა--}}
{{--                            </a>--}}
                        </div>
                    </div>
                </div>


                <div class="col-6">
                    <form action="" method="get" class="row">

                        <div class="form-group col-4">
                            <div class="form-group">
                                <div class="select2-purple">
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('administrator'))
                                        <select name="user"
                                                class="form-control review-change"
                                                data-placeholder="აირჩიეთ მომხმარებელი "
                                                data-dropdown-css-class="select2-purple" style="width: 100%;"

                                        >
                                            <option> აირჩიეთ მომხმარებლები </option>

                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"
                                                {!! request()->get('user') == $user->id ? 'selected' : '' !!}
                                                >{{$user->email}}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="category" value="{{request()->get('category')}}">

                        <div class="col-7 form-group" >
                            <div class="input-group input-group-md div " >
                                <input type="text" name="title" class="form-control float-right" placeholder="ძებნა" @if(request()->has('title')) value="{{request()->get('title')}}" @endif>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default filter-form">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>



                        <button style="visibility: hidden" type="submit" class="btn btn-default filter-form">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>

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
             @isset($list)
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
                            <th>დარეკა</th>
                            <th>ნახვები</th>
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


                                        <a href="https://serwish.ge/services/{{$l->id}}/from-admin" target="_blank" style="border:1px #000000 dashed; padding: 5px; border-radius: 5px; color:#000000">
                                            ID: 00{{$l->id}}
                                        </a>

                                    </div>

                                </td>
{{--                                <td>{{\Illuminate\Support\Str::limit($l->title,10)}}</td>--}}
                                <td>
                                     {!! \App\Helpers\Beautifier::getStatus($l) !!}
                                </td>
                                <td>
                                        {{$l->specialist->name}}
                                </td>
                                <td>
                                    @if($l->contact_number == null || $l->contact_number == "")
                                        {{$l->specialist->phone_number}}
                                    @else
                                        {{$l->contact_number}}
                                    @endif
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
                                <td style="color: red">

                                    <?php

                                        $date = str_replace('+',' ', request()->get('daterange'));
                                        $dates = explode(' - ',$date);
                                        $startDate = (!empty(request()->get('daterange'))) ? \Carbon\Carbon::parse($dates[0]) : \Carbon\Carbon::now()->startOfDay()->toDateTimeString();
                                        $endDate = (!empty(request()->get('daterange'))) ?  \Carbon\Carbon::parse($dates[1])->addDay() : \Carbon\Carbon::now()->endOfDay()->toDateTimeString();
                                   ?>

                                    {!! \App\Models\ServicePhoneClickStatistics::where('service_id','=',$l->id)
                                        ->where('created_at', '>=', $startDate)
                                        ->where('created_at', '<=', $endDate)
                                        ->count() !!}
                                </td>
                                <td style="color: red">
                                    {!! \App\Models\ServiceStatistics::where('service_id','=',$l->id)
                                        ->where('created_at', '>', $startDate)
                                        ->where('created_at', '<', $endDate)->count() !!}
                                </td>
                                <td>
                                    @if(
                                            \Carbon\Carbon::parse($l->review_date)->gt( \Carbon\Carbon::now()->subHours(24)->toDateTimeString() ) ||
                                            \Illuminate\Support\Facades\Auth::user()->hasRole('administrator') ||
                                            $l->review_status !==  'published'
                                            )

                                        @include('manager.partials.actionButton',[
                                                                                  'permission'=>'service',
                                                                              'editRoute'=>route('manager.services.service.show',[
                                                                                  'id'=>$l->id,
                                                                                  'pongBack'=>isset($pongBack) ? $pongBack : null
                                                                                  ]),
                                                                              'customEditName'=> 'დეტალურად',
                                                                              'deleteRoute'=>route('manager.services.service.destroy',[
                                                                                  'id'=>$l->id,
                                                                                  'pongBack'=>isset($pongBack) ? $pongBack : null

                                                                                  ]),
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
                 @endif
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            @isset($list)
            {!! $list->links() !!}
            @endif
        </div>
        <!-- /.card -->
    </div>
@endsection

@push('footer')
    <!-- Control Sidebar -->
    <!-- /.control-sidebar -->


    <style>
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

    </style>
@endpush

@push('script')



    <script>
        $(document).ready(function(){

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


        })

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


