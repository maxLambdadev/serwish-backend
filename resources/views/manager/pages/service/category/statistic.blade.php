
@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">კატეგორიები</li>
    <li class="breadcrumb-item active">სტატისტიკა</li>
@endpush
@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">სტატისტიკა</h3>
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
                            <a href="{{route('manager.services.category.statistics')}}">
                                <span style="font-size: 18px;">
                                     <i class="fas fa-times"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-6" style="padding-top:1%; visibility:hidden">
                            <div class="input-group input-group-md div " style="width: 150px;">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default filter-form">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>


                </div>

            </div>

            <div class="card-body" style="width: 100%">

                <div class="col-md-12">
                    <div class="panel panel-default">

                        <div class="panel-body">

                            <table class="table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th> კატეგორია </th>
                                    <th> განცხადებები </th>
                                    <th> ნახვები </th>
                                    <th> ონლაინ შეკვეთები </th>
                                    <th> დარეკვები </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $l)
                                    <tr data-toggle="collapse" data-target="#demo{{$l['id']}}" class="accordion-toggle">
                                        <td><button class="btn btn-default btn-xs"><span class="fas fa-eye"></span></button></td>
                                        <td>{{$l['translated'][0]['title']}}</td>
                                        <td> {{ $l['services_count']}} </td>
                                        <td> {{ $l['stat_back_count'] }} </td>
                                        <td> 0 </td>
                                        <td> {{ $l['call_requests_count'] }} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="12" class="hiddenRow">
                                            <div class="accordian-body collapse" id="demo{{$l['id']}}">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr class="info">
                                                        <th> კატეგორია </th>
                                                        <th> განცხადებები </th>
                                                        <th> ნახვები </th>
                                                        <th> ონლაინ შეკვეთები </th>
                                                        <th> დარეკვები </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($l['childrens'] as $child)
                                                        <tr data-toggle="collapse"  class="accordion-toggle">
                                                            <td> {{ $child['translated'][0]['title'] }}</td>
                                                            <td> {{$child['services_count']}} </td>
                                                            <td> {{ $child['stat_back_count'] ==  null ? 0 : $child['stat_back_count'] }} </td>
                                                            <td> 0 </td>
                                                            <td> {{$child['call_requests_count']}} </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
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
    </script>
@endpush

