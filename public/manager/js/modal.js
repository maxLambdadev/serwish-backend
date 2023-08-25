/**
 * @author jedy
 * @since 0.1
 */
class ModalBuilder {

    constructor( options ) {
        this.options = options;
        this.defaultOptions = {
            header: '',
            content: '',
            footer: '',
            selector: '',
            onShow: null,
            onShown: null,
            onHide: null,
            onHidden: null,
        }

        this.options.modalSelector = this.options.modal
        this.options.modal = $(this.options.modal)
        this.options = {...this.defaultOptions, ...this.options}

        return this.render();
    }


    render(){
        this.registerModalTrigger()

        this.build()
    }

    build(){
        this.buildHeader()
        this.buildBody()
        this.buildFooter()
    }

    push(content){
        this.options.content += content
    }

    buildHeader(){
        if (this.options.header !== '')
            $(`${this.options.modalSelector} .modal-header`).html(this.options.header)
    }

    buildBody(){
        $(`${this.options.modalSelector} .modal-body`).html(this.options.content)
    }

    buildFooter(){
        if (this.options.footer !== ''){
            $(`${this.options.modalSelector} .modal-footer`).html(this.options.footer)
        }else{
            $(`${this.options.modalSelector} .modal-footer`).hide()
        }
    }

    registerModalTrigger(){
        if (typeof this.options.on !== "undefined"){
            $(document).on('click', this.options.on, () => {
                $(this.options.modal).modal({show:true})
            })
        }

        if (typeof this.options.onShow === 'function'){
            $(document).on('show.bs.modal', this.options.modal,  (e) =>{
                this.options.onShow(e)
            })
        }

        if (typeof this.options.onShown === 'function'){
            $(document).on('shown.bs.modal', this.options.modal, (e) => {
                this.options.onShown(e)
            })
        }

        if (typeof this.options.onHide === 'function') {
            $(document).on('hide.bs.modal', this.options.modal, (e) => {
                this.options.onHide(e)
            })
        }

        if (typeof this.options.onHidden === 'function') {
            $(document).on('hidden.bs.modal', this.options.modal, (e) => {
                this.options.onHidden(e)
            })
        }
    }

    close(){
        this.options.modal.modal('hide')
    }

    open() {
        this.options.modal.modal({show: true})
    }

    static open(modal){
        $(modal).modal({show:true})
    }

    static close(modal){
        $(modal).modal('hide')
    }


}
