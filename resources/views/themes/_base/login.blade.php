<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
    @show

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/icon') }}/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/icon') }}/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/icon') }}/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/icon') }}/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/icon') }}/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/icon') }}/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/icon') }}/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/icon') }}/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/icon') }}/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/icon') }}/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icon') }}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/icon') }}/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/icon') }}/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('assets/icon') }}/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/icon') }}/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>{{ env('WEBSITE_NAME') }} | @yield('title')</title>

    @section('css')
        <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    @show
    @section('script-top')
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    @show
</head>
<body>

@yield('content')

@section('script-bottom')
    <script src="{{ asset('/assets/js/app.js') }}"></script>
    @if(session()->has('message'))
        <?php
        switch (session()->get('message_alert')) {
            case 2 : $type = 'success'; break;
            case 3 : $type = 'info'; break;
            default : $type = 'danger'; break;
        }
        ?>
        <script type="text/javascript">
            'use strict';
            $.notify({
                // options
                message: '{!! session()->get('message') !!}'
            },{
                // settings
                type: '{!! $type !!}',
                placement: {
                    from: "bottom",
                    align: "right"
                },
            });
        </script>
    @endif
    <script type="text/javascript">
        $('.tree-1').click(function() {
            let id = $(this).data('id');
            $('.tree-2.level-'+id).each(function(index, item) {
                let secondId = $(item).data('id');
                if ($('.tree-2.level-'+id).hasClass('open') && $('.tree-3.level-'+secondId).hasClass('open')) {
                    $('.tree-3.level-'+secondId).toggleClass('open');
                }
            });
            $('.tree-2.level-'+id).toggleClass('open');
        });

        $('.tree-2').click(function() {
            let id = $(this).data('id');
            $('.tree-3.level-'+id).toggleClass('open');
        });

        let tableOffset = $("#show-table-login").offset().top;
        let $header = $("#show-table-login > thead").clone();
        let $row = $("#show-table-login > tbody > tr").first().clone();
        let $fixedHeader = $("#header-fixed")
            .append($header)
            .append($("<tbody>").append($row));

        let setWidth = false;

        $(".portlet-body").scroll(function ()
        {
            $("#header-fixed").offset({ left: -1*this.scrollLeft });
        });

        $(window).bind("scroll", function() {
            let offset = $(this).scrollTop();

            if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
                $fixedHeader.show();
            } else if (offset < tableOffset) {
                $fixedHeader.hide();
            }

            if (setWidth === false) {
                let totalRow = 4;
                let startRow = 0;
                let totalWidth = 0;
                // setWidth = true;
                $('#show-table-login tr th').each(function(index, item) {

                    let getWidth = $(item).width();
                    if (startRow++ < totalRow) {
                        totalWidth += getWidth;
                    }

                    $('#header-fixed tr th').each(function(index2, item2) {
                        if(index === index2) {
                            $(item2).attr('style', 'width:' + getWidth + 'px;vertical-align: middle;text-align:center;')
                        }
                    });
                });

                $('#header-fixed').width($('#show-table-login').width());
            }
        });
    </script>
@show
</body>
</html>
