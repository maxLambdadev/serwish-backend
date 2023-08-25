
$(function (){


    $('.slug-from-input').each((k) => {
        let currentElement = $('.slug-from-input')[k]
        $(currentElement).keyup(function (){
            let parsed = castToSlug($(this).val())
            let slugInput = $(`.slug-input-${$(this).attr('data-locale')}`)
            slugInput.val(parsed)
        })
    })

    slugFrom = $('.slug-from-input:visible')



    // console.log($('[name="slug"]'))
    // console.log(currentLocale)
    // console.log(slugInput)

    function castToSlug(value){
        return value.toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[&\/\\#,+()$~%^.'":*?<>{}]/g,'-');


    }
})
