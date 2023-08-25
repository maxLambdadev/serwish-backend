$(function(){

    let multiple = $('.dropzone-fileinput-button').attr('data-multiple')

    let currentPage = 1;
    let lastPage = 2;

    let modal = new ModalBuilder({
        on: '.browse-media',
        modal: '#media-modal',
        content: ``,
        footer:`
                <button type="button" class="btn btn-default" data-dismiss="modal">დახურვა</button>
                <button type="button" class="btn btn-primary add-media">დამატება</button>
            `,
        onShow: (e) => {
            //just reset pagination
            currentPage = 1
            exploreMedia(currentPage)

        },
        onHide: (e) => {
            //reset content
            modal.options.content = ''
            modal.build()
        }
    })

    //infinity scroll
    $('.scrollable').scroll(function (){
        console.log(lastPage)
        console.log(currentPage)
        if (lastPage >= currentPage){
            if ( $('.scrollable').height() < $(this).scrollTop()) {
                ++currentPage
                exploreMedia(currentPage)
            }
        }
    })

    $(document).on('click','.delete', function(e){
        removeImageElement($(this))
    })

    $(document).on('click','.makeDefault', function(e){
        makeDefaultImage($(this))
    })

    $(document).on('click', '.media-item' ,function (e){

        if($(this).hasClass('selected-image')){
            $(this).removeClass('selected-image')
        }else{
            if (multiple === "0"){
                if ($('.selected-image').length <= 0){
                    $(this).addClass('selected-image')
                }
            }else{
                $(this).addClass('selected-image')
            }
        }
    })

    $(document).on('click','.add-media', function(e){
        if (multiple === "0" && $('.each-media-element').length > 0){
            return
        }

        $('.selected-image').each((k,e) => {
            mediaId = $(e).attr('data-id')
            mediaPath = $(e).attr('data-path')
            mediaName = $(e).attr('data-name')
            addMediaElement({
                id:mediaId,
                path:mediaPath,
                name:mediaName
            })
        })
        modal.close()
    })

    /**
     * update image metadata
     * sending ajax request
     */
    $(document).on('click','.update-image-button', function (e){
        $this = $(this)
        post('/panel/media/update',{
                name: $('.image-name').val(),
                id: $this.attr('data-id')
            },
            toastSuccessHandler,
            formErrorHandler
        )
    })

    $(document).on('click','.delete-image-button',function (e){
        let id = $('.update-image-button').attr('data-id')
        post('/panel/media/trash',
            { id:id },
            function (response) {
                $(`.media-item-${id}`).remove()
                $('.modal-sidebar').hide('slow')
            },
            formErrorHandler
        )
    })

    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/panel/media/store", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        // if :multiple=false
        if (multiple === "0") {
            if ( this.files.length > 1 ){
                this.removeFile(this.files[0]);
            }
            if ( $('.each-media-element').length > 1){
                $('[name="media[]"]').remove()
                $($('.each-media-element')[1]).parent().remove();
            }
        }
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function(file,xhr,formData) {
        formData.append("_token",  $('meta[name=csrf]').attr('content'));
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    myDropzone.on("complete", function (file){
        let response = JSON.parse(file.xhr.response)
        let element = $(file.previewElement.outerHTML)
        //update delete button data-id
        element.find('.delete').attr('data-id',response.media.id)
        file.previewElement.innerHTML = element.prop('outerHTML')

        addMediaElement({
            id:response.media.id
        })
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function() {
        myDropzone.removeAllFiles(true)
    }


    $(document).on('dblclick','.media-item' ,function(e) {
        $('.image-name').val($(this).attr('data-name'))
        $('.image-path-holder').attr('src',`/storage/${$(this).attr('data-path')}`)
        $('.update-image-button').attr('data-id',$(this).attr('data-id'))
        $('.modal-sidebar').show()
    })

    //close media edit panel
    $(document).on('click','.close-media-edit',(e) => $('.modal-sidebar').hide('slow'))

    //explore media
    /**
     * @param page
     */
    function exploreMedia(page){
        get(`/panel/media/explore?page=${page}`,{} , ( response ) => {
                let items = ''
                response.data.forEach((e,k)=>{
                    items += `
                              <div class="media-item media-item-${e.id}" data-name="${e.name}" data-path="${e.path}" data-id="${e.id}" style="background: url('/storage/${e.path}')"></div>
                        `
                })
                modal.push(items)
                modal.build()
            },
            ( error ) => {}
        )
    }

    function addMediaElement(media, selector = '.input-holders', imageSelector = '.image-holders'){
        $(selector).append(
            `<input type="hidden"  name="media${multiple !== "0" ? '[]' : '' }" value="${media.id}" class="media-${media.id}"  />`
        )
        if (media.hasOwnProperty('path')){
            $(imageSelector).append(
                `    <div class="table table-striped files " id="previews">
                            <div id="template" class="row mt-2">
                            <div class="col-auto each-media-element">
                                <span class="preview"><img src="/storage/${media.path}" alt="" data-dz-thumbnail width="80" height="80"/></span>
                            </div>
                            <div class="col d-flex align-items-start">
                                <p class="mb-0 stretch">
                                    <span class="lead small-text" data-dz-name>${media.name}</span>
                                </p>
                                <strong class="error text-danger" data-dz-errormessage></strong>
                            </div>

                            <div class="col-auto d-flex align-items-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary start" type="button" disabled>
                                        <i class="fas fa-upload"></i>
                                    </button>

                                    <button class="btn btn-danger delete" type="button" data-id="${media.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            )
        }
    }

    function removeImageElement(element){
        let mediaId = element.attr('data-id')
        $(`.media-${mediaId}`).remove()
        element.parent().parent().parent().remove()

        if (typeof mediaId !== "undefined" && typeof element.attr('data-other-id') !== "undefined"){
            let route = element.attr('data-route')
            let otherId = element.attr('data-other-id')
            let otherEntity = element.attr('data-other-entity')
            post(route,{
                resource_id:mediaId,
                other_id: otherId,
                other_entity: otherEntity
            },toastSuccessHandler,toastErrorHandler)
        }
    }

    function makeDefaultImage(element){
        let mediaId = element.attr('data-id')
        let isActive = element.attr('data-enable')
        if (typeof mediaId !== "undefined" && typeof element.attr('data-other-id') !== "undefined"){
            let route = element.attr('data-route')
            let otherId = element.attr('data-other-id')
            let otherEntity = element.attr('data-other-entity')
            post(route,{
                resource_id:mediaId,
                other_id: otherId,
                other_entity: otherEntity,
                is_active: isActive
            },function (response){
                if (parseInt(isActive) === 1){
                    $(element).html('<i class="fas fa-image"></i>')
                    $(element).attr('data-enable',"0")
                }else{
                    $(element).attr('data-enable',"1")
                    $(element).html('<i class="fas fa-images"></i>')
                }
                toastSuccessHandler(response)
            },toastErrorHandler)
        }
    }
})

