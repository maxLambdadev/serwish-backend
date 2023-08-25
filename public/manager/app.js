
$(document).ready(function (){


})


function validate(rules, messages){
    $.validator.setDefaults({
        submitHandler: function () {

        }
    });

    $('#quickForm').validate({
        rules: rules,
        messages: messages,
        errorElement: 'span',

        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
}



function successToast(title,body,subtitle = ''){
    toast(title,body,subtitle,'bg-success')
}

function errorToast(title,body,subtitle){
    toast(title,body,subtitle,'bg-danger')
}

function toast(title, body, subtitle = '', clazz = 'bg-danger'){

    $(document).Toasts('create', {
        class: clazz,
        title: title,
        subtitle: subtitle,
        body: body
    })
}

function swalConfirm(then){

    Swal.fire({
        title: 'დატრწმუნებული ხართ?',
        text: "წაშლის შემდეგ მონაცემს ვეღარ დააბრუნებთ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'დიახ წავშალოთ!'
    }).then((result) => {
        if (result.isConfirmed) {
            then()
            Swal.fire(
                'წაიშალა!',
                'ფაილი წარმატებით წაიშალა.',
                'success'
            )
        }
    })
}
