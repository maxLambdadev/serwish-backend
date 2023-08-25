@extends('manager.layouts.app')


@push('crumbs')
    <li class="breadcrumb-item active">{{__('users')}}</li>
@endpush
@section('section-title', 'კონტაქტები')

@section('panel')


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">სმს-ების გაგზავნა</h3>
            </div>
            <div class="card-body">

                <a class="btn btn-app message-send-global" data-type="employee" href="#" >
                    <i class="fas fa-play"></i> სპეციალისტებზე გაგზავნა
                </a>

                <a class="btn btn-app message-send-global" data-type="client" href="#" >
                    <i class="fas fa-play"></i> მომხმარებლებზე გაგზავნა
                </a>

                <a class="btn btn-app message-send-global" data-type="custom" href="#" >
                    <i class="fas fa-play"></i> სმს გაგზავნა
                </a>

                <a class="btn btn-app message-send-global" data-type="category" href="#" >
                    <i class="fas fa-play"></i> კატეგორიით გაგზავნა
                </a>

            </div>
        </div>
    </div>



@endsection

@section('content')


    <section class="content">

        <div class="card card-solid">
            <div class="card-body pb-0">
                <div class="row">
                    @foreach($contacts as $contact)
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                            <div class="card bg-light d-flex flex-fill">
                                <div class="card-header text-muted border-bottom-0">
                                    {{$contact->client_type == "employee" ? 'სპეციალისტი' : 'მომხმარებელი'}}
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="lead"><b>{{$contact->name}}</b></h2>
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> {{$contact->personal == 'personal' ? 'პირადი ნომერი: ' : 'საიდენტიფიკაციო ნომერი: '}}
                                                    {{$contact->id_number}}</li>
                                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> ტელ #:
                                                    {{$contact->phone_number}}</li>
                                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span>
                                                    {{$contact->email}}</li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="#" class="btn btn-sm bg-teal send-message" data-id="{{$contact->id}}">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-footer">
               {!! $contacts->links() !!}
            </div>

        </div>

    </section>
@endsection

@push('script')

    <div class="modal fade" id="send-custom-modal">

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">სმს შეტყობინება</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="smsType" value="default" />

                        <div class="form-group">
                            <label for="users">მომხმარებლები</label>
                            <select class="js-example-basic-multiple form-control" id="multiUsers" name="users[]" multiple="multiple">
                                @foreach($users as $u)
                                    <option value="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="message">მესიჯი ლიმიტი <span id="message-custom-limit">1000</span> </label>
                            <textarea name="message" rows="5" cols="20" id="custom-message" cols="30" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">დახურვა</button>
                    <button type="button" class="btn btn-primary send-custom-btn">გაგზავნა</button>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="send-message-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">სმს შეტყობინება</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="smsType" value="default" />

                        <div class="form-group">
                            <label for="message">მესიჯი ლიმიტი <span id="message-limit">1000</span> </label>
                            <textarea name="message" rows="5" cols="20" id="message" cols="30" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">დახურვა</button>
                    <button type="button" class="btn btn-primary send-sms-btn">გაგზავნა</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send-category-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">სმს შეტყობინება</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="smsType" value="default" />

                        <div class="form-group">
                            <label for="check">სერვიშ ხარისხი</label>
                            <input type="checkbox" id="check" class="custom-checkbox" name="serwish-quality">
                        </div>

                        <div class="form-group">
                            <label for="users">კატეგორია</label>
                            <select class="js-example-basic-multiple form-control" id="categories" name="category" style="padding:10px">
                                <option value="0" selected>აირჩიეთ კატეგორია</option>
                                @foreach($categories as $u)
                                    <option value="{{$u->id}}">{{$u->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="users">აირჩიეთ მომხმარებლები</label>
                            <select class="js-example-basic-multiple form-control" id="cat-users" name="catUsers[]" multiple="multiple">

                            </select>

                        </div>

                        <div class="form-group">
                            <label for="message">მესიჯი ლიმიტი <span id="message-cat-limit">1000</span> </label>
                            <textarea name="message" rows="5" cols="20" id="cat-message" cols="30" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">დახურვა</button>
                    <button type="button" class="btn btn-primary send-cat-btn">გაგზავნა</button>
                </div>
            </div>
        </div>

    </div>

    <script>

        $('.js-example-basic-multiple').select2();

        // $('.category-simple').select2();

        const messageLengthLimit = 1000
        let currentContactId = null
        let messageTypeInput = $('input[name="smsType"]')

        let messageLimit = $('#message-limit')
        let customMessageLimit = $('#message-custom-limit')
        let catMessageLimit = $('#message-cat-limit')
        let message = $('#message')
        let customMessage = $('#custom-message')
        let catMessage = $('#cat-message')





        message.on('input',function(){
            let inputLength = $(this).val().length
            let nextLength = messageLengthLimit - inputLength

            if(nextLength <= 0){
                messageLimit.html(0)
                message.val(message.val().substring(0,messageLengthLimit))
            }else{
                messageLimit.html(nextLength)
            }
        })

        customMessage.on('input',function(){
            let inputLength = $(this).val().length
            let nextLength = messageLengthLimit - inputLength

            if(nextLength <= 0){
                customMessageLimit.html(0)
                customMessage.val(message.val().substring(0,messageLengthLimit))
            }else{
                customMessageLimit.html(nextLength)
            }
        })

        catMessage.on('input',function(){
            let inputLength = $(this).val().length
            let nextLength = messageLengthLimit - inputLength

            if(nextLength <= 0){
                catMessageLimit.html(0)
                catMessage.val(catMessage.val().substring(0,messageLengthLimit))
            }else{
                catMessageLimit.html(nextLength)
            }
        })

        $('.send-message').click(function (){
            messageLimit.html(messageLengthLimit)
            message.val("")
            messageTypeInput.val("default")

            currentContactId = $(this).attr('data-id')
            $('#send-message-modal').modal({show:true})
        })

        $('.message-send-global').click(function(e){

            messageLimit.html(messageLengthLimit)

            e.preventDefault()
            message.val('')
            currentContactId = null
            messageTypeInput.val($(this).attr('data-type'))

            if($(this).attr('data-type') === "custom"){
                $('#send-custom-modal').modal({show:true})
            }else if($(this).attr('data-type') === "category") {
                $('#send-category-modal').modal({show:true})
            }else{
                $('#send-message-modal').modal({show:true})
            }
        })

        $('.send-custom-btn').click(function (e){

            e.preventDefault()
            let data = {
                _token: $('meta[name=csrf]').attr('content'),
                users: $('#multiUsers').val(),
                message: customMessage.val()
            }

            $.ajax({
                type: 'POST',
                url: "/panel/users/contact/send-custom-sms",
                data: data,
                success: (response) => {
                    successToast('გაიგზავნა', 'სმს-ი წარმატრებით გაიგზავნა')
                    $('#send-message-modal').modal('hide')
                },
                error: (error) => {
                    errorToast('გაიგზავნა', error.responseJSON.message,'არასწორი პარამეტრები')
                }
            });

        })

        $('.send-sms-btn').click(function (e){

            e.preventDefault()
            let data = {
                _token: $('meta[name=csrf]').attr('content'),
                smsType: messageTypeInput.val(),
                userId: currentContactId,
                message: message.val()
            }

            $.ajax({
                type: 'POST',
                url: "/panel/users/contact/send-sms",
                data: data,
                success: (response) => {
                    successToast('გაიგზავნა', 'სმს-ი წარმატრებით გაიგზავნა')
                    $('#send-message-modal').modal('hide')
                },
                error: (error) => {
                    errorToast('გაიგზავნა', error.responseJSON.message,'არასწორი პარამეტრები')
                }
            });

        })

        $('.send-cat-btn').click(function (e){

            e.preventDefault()
            let data = {
                _token: $('meta[name=csrf]').attr('content'),
                users: $('select[name="catUsers[]"]').val(),
                message: catMessage.val()
            }

            $.ajax({
                type: 'POST',
                url: "/panel/users/contact/send-custom-sms",
                data: data,
                success: (response) => {
                    successToast('გაიგზავნა', 'სმს-ი წარმატრებით გაიგზავნა')
                    $('#send-message-modal').modal('hide')
                },
                error: (error) => {
                    errorToast('გაიგზავნა', error.responseJSON.message,'არასწორი პარამეტრები')
                }
            });

        })

        $('#check').on('change',function (){

            let serwishQ = $(this).is(':checked')

            if($('#categories').val() !== "0"){

                let data = {
                    _token: $('meta[name=csrf]').attr('content'),
                    category: $('#categories').val(),
                    serwishQ: serwishQ === true ? 'on' : 'off'
                }
                $.ajax({
                    type: 'POST',
                    url: "/panel/users/contact/users/by/categories",
                    data: data,
                    success: (response) => {
                        console.log(response.users)
                        $('select[name="catUsers[]"]').empty()
                        for (let i  = 0 ; i < response.users.length; i++){
                            let newOption = new Option(response.users[i].email, response.users[i].id, false, false);
                            $('select[name="catUsers[]"]').append(newOption).trigger('update');
                        }
                    },
                    error: (error) => {

                    }
                });

            }

        })

        $('#categories').on('change',function (){
            let serwishQ = $('#check').is(':checked')

            let data = {
                _token: $('meta[name=csrf]').attr('content'),
                category: $(this).val(),
                serwishQ: serwishQ === true ? 'on' : 'off'
            }
            $.ajax({
                type: 'POST',
                url: "/panel/users/contact/users/by/categories",
                data: data,
                success: (response) => {
                    console.log(response.users)
                    $('select[name="catUsers[]"]').empty()
                    for (let i  = 0 ; i < response.users.length; i++){
                        let newOption = new Option(response.users[i].email, response.users[i].id, false, false);
                        $('select[name="catUsers[]"]').append(newOption).trigger('update');
                    }
                },
                error: (error) => {

                }
            });
        })
    </script>
@endpush
