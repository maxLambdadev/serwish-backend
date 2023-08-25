
$(document).ready(function (){



    $('.ajax-form').submit(function (e){
        e.preventDefault();
        post(
            $(this).attr('action'),
            $(this).serialize(),
            formSuccessHandler,
            formErrorHandler
        )
    })

    $('#bulkCheck').on('click',function () {
        let state = false;
        let selector = '.check' // default selector for checkboxes

        /**
         * custom attribute feature (ex: data-selector='customInputClass')
         */
        if(typeof $(this).attr('data-selector') !== "undefined"){
            selector = $(this).attr('data-selector');
        }

        //if checked returns true
        if (!$(selector).prop('checked')) {
            state = true
        }

        $(selector).prop('checked', state);
        $(this).prop('checked', state);
    });

    $('.deleteTrigger').on('click',function (e){
        e.preventDefault();
        var route = $(this).attr('href')

        swalConfirm(function (){
            deleteRequest(route,deleteSuccessHandler)
        })
    })

    $('#bulkDelete').on('click',function(){
        var route = $(this).attr('data-route')
        swalConfirm(function (){
            let ids = [];

            let selector = '.check' // default selector for checkboxes

            /**
             * custom attribute feature (ex: data-selector='customInputClass')
             */
            if(typeof $(this).attr('data-selector') !== "undefined"){
                selector = $(this).attr('data-selector');
            }

            $(selector).each(function(){
                if ($(this).is(':checked'))
                {
                    ids.push($(this).val())
                }
            });

            post(
                route,
                {ids:ids},
                deleteSuccessHandler,
                formErrorHandler
            )
        })

    });

});


