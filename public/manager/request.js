let csrf = $('meta[name=csrf]').attr('content')

function post(url, data, success, error){
    if (!data.hasOwnProperty('_token')){
        data._token = csrf;
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: success,
        error: error
    });
}

function get(url, data, success, error){

    $.ajax({
        type: 'GET',
        url: url,
        data: data,
        success: success,
        error: error
    });
}


function deleteRequest(url,success){
    $.ajax({
        type: 'DELETE',
        url: url,
        success: success,
        headers:{'X-CSRF-TOKEN':csrf},
        error: formErrorHandler
    });
}

function postRequest(url, data,success){
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: success,
        headers:{'X-CSRF-TOKEN':csrf},
        error: formErrorHandler
    });
}



function deleteSuccessHandler(resp)
{
    resp.body.ids.forEach((id) => {
        $('#tr-'+id).hide(1);
    })
}


function formSuccessHandler(success){
    successToast('განახლება',success.message,'Success Message')

    if (success.hasOwnProperty('redirect') && success.redirect !== null){

        setTimeout(function (){
            window.location.href = success.redirect
        },300)
    }
}

function formErrorHandler(error){
    let errorMsg = "<ul>";

    if (error.responseJSON.hasOwnProperty('errors')){
        errors = error.responseJSON.errors

        for (let err in errors){
            errorMsg += '<li> '+ errors[err][0] +' </li>';
        }
        errorMsg += "</ul>"
    }else{
        errorMsg = error.responseJSON.message;
    }
    errorToast('Error Occurred',errorMsg,'Validation Error')
}

function toastErrorHandler(response){
    errorToast('Error', response.message, 'Error Occurred')
}

function toastSuccessHandler(response){
    successToast('Success',response.message, 'Success Message')
}











