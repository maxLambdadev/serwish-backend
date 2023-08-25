{{--*** move all styles and scripts to different file--}}
<style>
    .dot {
        height: 12px;
        width: 12px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        vertical-align: middle;
        margin-top: .5%;
        position: absolute;
    }
    .red{
        background-color: #F47B7A;
    }
    .green{
        background-color: #6FCF97;
    }

</style>
<script>
    $(function(){
        $('.hint').tooltip();
    });
</script>

@if($entity[$statusElement->key])
    <div class="dot green hint center"  data-toggle="tooltip" data-placement="top" title="ჩართული"></div>
@else
    <div class="dot red hint center"  data-toggle="tooltip" data-placement="top" title="გამორთული"></div>
@endif
{{--{!! dd($statusElement,$entity) !!}--}}
