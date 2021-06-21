<html>
<head>
    <style>
        @page { margin: 100px 25px; }
        header { position: fixed; top: -60px; left: 0px; right: 0px; height: 50px; text-align: center; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; text-align: center; font-style: italic; color: #c0c0c0 }
        main {
            font-size: 12px;
        }
        .underline {
            padding-bottom: 10px;
            border-bottom: 2px #000 solid;
        }
        .pagenum:before {
            content: counter(page);
        }
        body {
            line-height: 0.5;
        }
        div {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            line-height: 1;
        }
        p {
            line-height: 1;
            padding-bottom: 0;
        }
        .no-padding {
            margin: auto;
        }
        .table {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            line-height: 1;
            table-layout: fixed;
        }
        .table.no-line-height {
            line-height: 0.5;
        }
        .table td {
            padding: 5px;
        }
        .border {
            border: 1px #000000 solid;
        }
        .border td {
            border: 1px #000000 solid;
            vertical-align: top;
        }
        .no-border {
            border: 0;
        }
        .left {
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .center {
            text-align: center;
        }
        .ttd_field {
            padding-top: 60px;
        }
        .padding5 {
            padding-top: 5px;
        }
        .padding10 {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .padding20 {
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .padding30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .break-all {
            word-break: break-all;
            word-wrap: break-word
        }
        .text-first {
            font-size: 16px;
        }
        .half-line-height {
            line-height: 0.5;
        }
        .sample {
            background-image: url("{!! asset('assets/images/draft.jpg') !!}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

    </style>
</head>
<body<?php echo isset($sample) && $sample == true ? ' class="sample"' : null; ?>>

@yield('content')

</body>
</html>
