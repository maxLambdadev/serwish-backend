
<!-- jQuery -->
<script src="{{asset('manager/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('manager/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('manager/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('manager/plugins/chart.js/Chart.min.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('manager/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('manager/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('manager/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('manager/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('manager/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('manager/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('manager/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('manager/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('manager/plugins/jquery-validation/additional-methods.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('manager/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('manager/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('manager/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

<script src="{{asset('manager/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>

<script src="{{asset('manager/plugins/dropzone/min/dropzone.min.js')}}"></script>
<!-- AdminLTE App -->

<script src="{{asset('manager/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('manager/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('manager/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('manager/dist/js/pages/dashboard.js')}}"></script>

<script src="{{asset('manager/js/slug.js')}}"></script>
<script src="{{asset('manager/js/language-switcher.js')}}"></script>
<script src="{{asset('manager/request.js')}}"></script>
<script src="{{asset('manager/app.js')}}"></script>
<script src="{{asset('manager/triggers.js')}}"></script>

<script>
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })


    $(document).ready(function (){
        $('[data-toggle="tooltip"]').tooltip()

        if ($(window).width() < 1819) {
            $("#toggle-sidebar-nav").click();

        }



        $('.content-wrapper').not('div.category-overlay-button').click(function (e){
            $('.control-sidebar').hide()
            $('.sub-cat-overlay').hide()
        })
    })

</script>
@stack('script')

