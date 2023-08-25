
$(function (){

    $('.summernote-active').summernote({
        height:240
    })

    /**
     * when language switcher changes
     * getting all input with input-picker and changes his names with ex : old ka[name] new en[name]
     */
    $('.languageSwitcher').change(function (e){
        e.preventDefault()
        let newLang =  $(this).val()

        //change all language picker value
        $(`.languageSwitcher option`).attr('selected',false)
        $(`.languageSwitcher option[value=${newLang}]`).prop('selected','selected')

        // hide/show language fields
        $('.input-picker').each((k,e) => {
            let element = $(e)
            let splittedName = element.attr('name').split('[');

            if (typeof splittedName[1] !== "undefined"){
                let inputName = splittedName[1].split(']')[0];
                let nextSelector = `[name='${newLang}[${inputName}]']`;
                console.log(nextSelector)

                if (element.hasClass('summernote-active')){
                    $('.summernote-active').summernote('destroy')
                    element.removeClass('summernote-active')
                    $(nextSelector).addClass('summernote-active')
                }
                element.hide()
                $(nextSelector).show()
            }


        })

        //todo reset wyswig editor
        $('.summernote-active').summernote()
    })



})
