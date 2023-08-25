@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">კოფიგურაცია</li>
    <li class="breadcrumb-item">SmsOffice</li>

    <li class="breadcrumb-item active">პარამეტრები</li>
@endpush
@section('content')


    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">ბალანსი</span>
                <span class="info-box-number">{{$balance}} SMS</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.configuration.smsoffice.basic.save')}}" method="post">
            <input type="hidden" name="redirect" value="{{route('manager.configuration.smsoffice.basic')}}">

            <!-- jquery validation -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">პარამეტრები </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @csrf

                        <div class="card-body">
                            <x-form.input name="apiKey"
                                          :title="__('panel.apiKey')"
                                          placeholder="შეიყვანეთ გასაღები"
                                          :entity="isset($entity) ? $entity : null"
                                          entityKey="apiKey"
                            />

                            <x-form.input name="callbackUrl"
                                          :title="__('panel.callbackUrl')"
                                          placeholder="შეიყვანეთ პასუხის ვებ-მისამართი"
                                          :entity="isset($entity) ? $entity : null"
                                          entityKey="callbackUrl"
                            />



                            <div class="form-group">
                                <label for="registerText">სმს კოდის ტექსტი</label>
                                <p>ტექსტი რომელიც დაიწერება კოდის გაგზავნის დროს <code>{code}</code> ტექსტში ჩაწერეთ იქ სადაც გინდათ რომ ჩაიწეროს კოდი</p>
                                <textarea name="registerText" id="" cols="5" rows="5" class="form-control">{!! isset($entity) && isset($entity->registerText) ? $entity->registerText : '' !!}</textarea>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">შენახვა</button>
                        </div>
                    </div>
                </div>

        </form>

    </div>

@endsection

