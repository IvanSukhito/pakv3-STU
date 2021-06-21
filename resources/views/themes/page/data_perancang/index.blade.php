@extends('themes._base.login')

@section('title', __('Data Perancang'))

@section('css')
    @parent
    <style>
        @import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
        .login-block{
            float:left;
            width:100%;
            padding : 50px 0;
            height: 100%;
        }
        .banner-sec{
            min-height:500px;
            border-radius: 10px;
            padding:10px;}
        .container{background:#fff; border-radius: 10px;border:1px solid #DADADa;}
        .carousel-inner{border-radius:0 10px 10px 0;}
        .carousel-caption{text-align:left; left:5%;}
        .login-sec{padding: 50px 30px; position:relative;}
        .login-sec .copy-text{position:absolute; width:80%; bottom:15px; font-size:12px; text-align:center;color:rgb(128, 125, 125);}
        .login-sec .copy-text i{color:#FEB58A;}
        .login-sec .copy-text a{color:#E36262;}
        .login-sec h2{margin-bottom:30px; font-weight:800; font-size:30px; color: #DE6262;}
        .login-sec h2:after{content:" "; width:100px; height:5px; background:#FEB58A; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
        .btn-login{background: #DE6262; color:#fff; font-weight:600;}
        .banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
        .banner-text h2{color:#fff; font-weight:600;}
        .banner-text h2:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
        .banner-text p{color:#fff;}
        .btn {
            width: 100%;
            flex: 1 1 auto;
            margin-top:20px ;
            padding: 10px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;
            /* text-shadow: 0px 0px 10px rgba(0,0,0,0.2);*/
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
        }
        /* Demo Stuff End -> */
        /* <- Magic Stuff Start */
        .btn:hover {
            background-position: right center; /* change the direction of the change here */
        }
        .btn-1 {
            background-image: linear-gradient(to right, #f6d365 0%, #fda085 51%, #f6d365 100%);
        }

        .btn-2 {
            background-image: linear-gradient(to right, #fbc2eb 0%, #a6c1ee 51%, #fbc2eb 100%);
        }

        .btn-3 {
            background-image: linear-gradient(to right, #03be48 0%, #8fd3f4 51%, #84fab0 100%);
        }

        .btn-4 {
            background-image: linear-gradient(to right, #a1c4fd 0%, #c2e9fb 51%, #a1c4fd 100%);
        }
        .btn-5 {
            background-image: linear-gradient(to right, #ffecd2 0%, #fcb69f 51%, #ffecd2 100%);
        }
        body {
            font-family: "Open Sans";
            /* background: url(resources/images/b1090242550d49f83c4086664191e903270634f4.png) no-repeat center center fixed;  */
            background:linear-gradient(0deg,rgba(5, 0, 0, 0.5),rgba(5, 0, 0, 0.5)),url({{ asset('assets/images/b1090242550d49f83c4086664191e903270634f4.png') }}) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .color-black {
            color: black;
        }

        .title {
            font-weight: 600;
        }

        table{
            font-size: 12px;
        }

        table thead{
            background-color: rgb(0, 110, 255) !important;
            color: white;
        }

        .total {
            background-color: rgb(0, 110, 255) !important;
            color: white;
        }

        .subtotal {
            background-color: rgb(77, 144, 231) !important;
            color: white;
        }

        .highlight {
            background-color: rgb(0, 202, 238) !important;
            color:black;
        }

        .level-1 {
            background-color: rgb(0, 202, 238) !important;
            color: white;
            font-weight: bold;
        }
        .level-1.open {
            background-color: rgb(51, 153, 255) !important;
            color: white;
            font-weight: bold;
        }
        .level-2 {
            background-color: rgb(0, 202, 238) !important;
            color: white;
            font-weight: bold;
        }
        .level-2.open {
            background-color: rgb(51, 153, 255) !important;
            color: white;
            font-weight: bold;
        }
        .level-3 {
            background-color: rgb(0, 202, 238) !important;
            color: white;
            font-weight: bold;
        }
        .level-3.open {
            background-color: rgb(51, 153, 255) !important;
            color: white;
            font-weight: bold;
        }

        .tree-1{
            cursor: pointer;
        }

        .tree-2{
            visibility: collapse;
            cursor: pointer;
        }
        .tree-3{
            visibility: collapse;
        }

        .tree-2.open{
            visibility: visible;
        }
        .tree-3.open{
            visibility: visible;
        }

        .tree-1:hover{
            cursor:pointer;
        }
        .tree-2:hover{
            cursor:pointer;
        }
        .tree-3:hover{
            cursor:pointer;
        }
    </style>
@show

@section('content')
    <section class="login-block">
        <div style="margin: auto;margin-left:100px;margin-bottom:20px;">
            <a style="font-size: 18px;color:#03ee59;" href="{{ route('admin.login') }}"><b style="text-align: right;"><<< Kembali ke Login</b></a>
        </div>
        <div class="container" style="max-width: 100%;max-height: 100%; ">
            <div class="row">
                <div class="col-sm-12 col-md-12 banner-sec">
                    <table class="display table table-bordered table-striped" id="show-table-login">
                        <thead>
                        <tr>
                            <th rowspan="3" style="text-align: center;">Unit Kerja</th>
                            <th colspan="2" style="text-align: center;">Jenis Kelamin</th>
                            <th colspan="{{ $total_merge['total'] }}" style="text-align: center;">JENJANG JABATAN FUNGSIONAL</th>
                            <th rowspan="3" style="text-align: center;">Total</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="text-align: center;">L</th>
                            <th rowspan="2" style="text-align: center;">P</th>
                            @foreach($list_jenjang as $value)
                                @if (isset($list_jenjang_golongan[$value]))
                                    <th colspan="{{ count($list_jenjang_golongan[$value]) }}" style="text-align: center;">{{ $data_jenjang_perancang[$value] }}</th>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($list_jenjang as $jenjang_id)
                                @if (isset($list_jenjang_golongan[$jenjang_id]))
                                    @foreach($list_jenjang_golongan[$jenjang_id] as $id)

                                        @if (isset($data_golongan[$id]))
                                            <th style="text-align: center;">{{ isset($data_golongan[$id]) ? $data_golongan[$id] : '' }}</th>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_jenjang = [];
                        $total_all = 0;
                        $total_all_2 = 0;
                        $a = 1;
                        $i1 = 0;
                        ?>
                        @foreach ($list_tree as $list_tree_name => $list_tree2)

                            <?php
                            $total_man = isset($list_tree2['additional']['total_man']) ? $list_tree2['additional']['total_man'] : 0;
                            $total_women = isset($list_tree2['additional']['total_women']) ? $list_tree2['additional']['total_women'] : 0;
                            $total_tree2 = $total_man + $total_women;
                            ?>
                            <tr class="tree-1 level-@php echo $a @endphp {{ $i1++ % 2 ? 'even' : 'odd' }}" data-id="@php echo $a @endphp">
                                <td>{{ $list_tree_name }}</td>
                                <td style="text-align: center;">{{ number_format($total_man, 0) }}</td>
                                <td style="text-align: center;">{{ number_format($total_women, 0) }}</td>
                                @foreach($list_jenjang as $jenjang_id)
                                    @if (isset($total_merge['total_'.$jenjang_id]))
                                        <?php
                                        $total_row_jenjang = $total_merge['total_'.$jenjang_id];
                                        $get_total_jenjang = isset($list_tree2['additional'][$jenjang_id]) ? $list_tree2['additional'][$jenjang_id] : 0;
                                        ?>
                                        <td colspan="{{ $total_row_jenjang }}" style="text-align: center;">{{ number_format($get_total_jenjang, 0) }}</td>
                                    @endif
                                @endforeach
                                <td style="text-align: right;">{{ number_format($total_tree2, 0) }}</td>
                            </tr>

                            @if (isset($list_tree2['list']))
                                @php
                                    $b =1;
                                    $i2 = 0;
                                @endphp
                                @foreach ($list_tree2['list'] as $list_tree_name2 => $list_tree3)

                                    <?php
                                    $total_man = isset($list_tree3['additional']['total_man']) ? $list_tree3['additional']['total_man'] : 0;
                                    $total_women = isset($list_tree3['additional']['total_women']) ? $list_tree3['additional']['total_women'] : 0;
                                    $total_tree3 = $total_man + $total_women;
                                    ?>
                                    <tr class="tree-2 level-@php echo $a @endphp {{ $i2++ % 2 ? 'even' : 'odd' }}" data-id="@php echo $a.'-'.$b @endphp">
                                        <td>  {{ $list_tree_name2 }}</td>
                                        <td style="text-align: center;">{{ number_format($total_man, 0) }}</td>
                                        <td style="text-align: center;">{{ number_format($total_women, 0) }}</td>
                                        @foreach($list_jenjang as $jenjang_id)
                                            @if (isset($total_merge['total_'.$jenjang_id]))
                                                <?php
                                                $total_row_jenjang = $total_merge['total_'.$jenjang_id];
                                                $get_total_jenjang = isset($list_tree3['additional'][$jenjang_id]) ? $list_tree3['additional'][$jenjang_id] : 0;
                                                ?>
                                                <td colspan="{{ $total_row_jenjang }}" style="text-align: center;">{{ number_format($get_total_jenjang, 0) }}</td>
                                            @endif
                                        @endforeach
                                        <td style="text-align: right;">{{ number_format($total_tree3, 0) }}</td>
                                    </tr>

                                    @if (isset($list_tree3['data']))
                                        @php
                                            $c =1;
                                            $i3 =1;
                                        @endphp
                                        @foreach ($list_tree3['data'] as $unit_kerja)
                                            @if (isset($list_data[$unit_kerja]))
                                                <?php
                                                $list = $list_data[$unit_kerja];
                                                $total_unit_kerja_man = isset($list['total_man']) ? intval($list['total_man']) : 0;
                                                $total_unit_kerja_women = isset($list['total_women']) ? intval($list['total_women']) : 0;
                                                $total_unit_kerja_pegawai = $total_unit_kerja_man + $total_unit_kerja_women;
                                                $total_all += $total_unit_kerja_pegawai;
                                                ?>
                                                <tr class="tree-3 level-@php echo $a.'-'.$b @endphp {{ $i3 % 2 ? 'even' : 'odd' }}">
                                                    <td rowspan="2">    {{ isset($data_unit_kerja[$unit_kerja]) ? $data_unit_kerja[$unit_kerja] : $unit_kerja }}</td>
                                                    <td rowspan="2" style="text-align: center;">{{ number_format($total_unit_kerja_man, 0) }}</td>
                                                    <td rowspan="2" style="text-align: center;">{{ number_format($total_unit_kerja_women, 0) }}</td>
                                                    @foreach($list_jenjang as $jenjang_id)
                                                        @if (isset($total_merge['total_'.$jenjang_id]))
                                                            <?php
                                                            $total_unit_kerja_jenjang = isset($list[$jenjang_id]['total']) ? $list[$jenjang_id]['total'] : 0;

                                                            $total_jenjang['total_'.$jenjang_id] = isset($total_jenjang['total_'.$jenjang_id]) ? $total_jenjang['total_'.$jenjang_id] : 0;
                                                            $total_jenjang['total_'.$jenjang_id] += $total_unit_kerja_jenjang;

                                                            $total_row_jenjang = $total_merge['total_'.$jenjang_id];
                                                            ?>
                                                            <td colspan="{{ $total_row_jenjang }}" style="text-align: center;">{{ number_format($total_unit_kerja_jenjang, 0) }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td rowspan="2" style="text-align: right;">{{ number_format($total_unit_kerja_pegawai, 0) }}</td>
                                                </tr>
                                                <tr class="tree-3 level-@php echo $a.'-'.$b @endphp {{ $i3++ % 2 ? 'even' : 'odd' }}" >
                                                    @foreach($list_jenjang as $jenjang_id)
                                                        @if(empty($jenjang_id))
                                                        @foreach($list_jenjang_golongan[$jenjang_id] as $id)
                                                            <?php
                                                            $total_golongan = isset($list[$jenjang_id]['golongan'][$id]) ? $list[$jenjang_id]['golongan'][$id] : 0;

                                                            $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] = isset($total_jenjang['total_gol_'.$jenjang_id.'_'.$id]) ? $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] : 0;
                                                            $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] += $total_golongan;
                                                            ?>
                                                            <td style="text-align: center;">{{ number_format($total_golongan, 0) }}</td>
                                                        @endforeach
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endif
                                            @php $c++; @endphp
                                        @endforeach
                                    @endif
                                    @php $b++; @endphp
                                @endforeach
                            @endif

                            @if (isset($list_tree2['data']))
                                @php
                                    $b =1;
                                    $i2 =1;
                                @endphp
                                @foreach ($list_tree2['data'] as $unit_kerja)
                                    @if (isset($list_data[$unit_kerja]))
                                        <?php
                                        $list = $list_data[$unit_kerja];
                                        $total_unit_kerja_man = isset($list['total_man']) ? intval($list['total_man']) : 0;
                                        $total_unit_kerja_women = isset($list['total_women']) ? intval($list['total_women']) : 0;
                                        $total_unit_kerja_pegawai = $total_unit_kerja_man + $total_unit_kerja_women;
                                        $total_all += $total_unit_kerja_pegawai;
                                        ?>
                                        <tr class="tree-2 level-@php echo $a @endphp {{ $i2 % 2 ? 'even' : 'odd' }}">
                                            <td rowspan="2"> {{ isset($data_unit_kerja[$unit_kerja]) ? $data_unit_kerja[$unit_kerja] : $unit_kerja }}</td>
                                            <td rowspan="2" style="text-align: center;">{{ number_format($total_unit_kerja_man, 0) }}</td>
                                            <td rowspan="2" style="text-align: center;">{{ number_format($total_unit_kerja_women, 0) }}</td>
                                            @foreach($list_jenjang as $jenjang_id)
                                                @if (isset($total_merge['total_'.$jenjang_id]))
                                                    <?php
                                                    $total_unit_kerja_jenjang = isset($list[$jenjang_id]['total']) ? $list[$jenjang_id]['total'] : 0;

                                                    $total_jenjang['total_'.$jenjang_id] = isset($total_jenjang['total_'.$jenjang_id]) ? $total_jenjang['total_'.$jenjang_id] : 0;
                                                    $total_jenjang['total_'.$jenjang_id] += $total_unit_kerja_jenjang;

                                                    $total_row_jenjang = $total_merge['total_'.$jenjang_id];
                                                    ?>
                                                    <td colspan="{{ $total_row_jenjang }}" style="text-align: center;">{{ number_format($total_unit_kerja_jenjang, 0) }}</td>
                                                @endif
                                            @endforeach
                                            <td rowspan="2" style="text-align: right;">{{ number_format($total_unit_kerja_pegawai, 0) }}</td>
                                        </tr>
                                        <tr class="tree-2 level-@php echo $a @endphp {{ $i2++ % 2 ? 'even' : 'odd' }}" >
                                            @foreach($list_jenjang as $jenjang_id)
                                                @foreach($list_jenjang_golongan[$jenjang_id] as $id)
                                                    <?php
                                                    $total_golongan = isset($list[$jenjang_id]['golongan'][$id]) ? $list[$jenjang_id]['golongan'][$id] : 0;

                                                    $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] = isset($total_jenjang['total_gol_'.$jenjang_id.'_'.$id]) ? $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] : 0;
                                                    $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] += $total_golongan;
                                                    ?>
                                                    <td style="text-align: center;">{{ number_format($total_golongan, 0) }}</td>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                    @endif
                                    @php $b++; @endphp
                                @endforeach
                            @endif
                            @php $a++; @endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="subtotal">
                            <th rowspan="2">Subtotal</th>
                            <th rowspan="2" style="text-align: center;">{{ number_format($total_man, 0) }}</th>
                            <th rowspan="2" style="text-align: center;">{{ number_format($total_women, 0) }}</th>
                            @foreach($list_jenjang as $jenjang_id)
                                @if (isset($total_merge['total_'.$jenjang_id]))
                                    <?php
                                    $get_total_jenjang = isset($total_jenjang['total_'.$jenjang_id]) ? $total_jenjang['total_'.$jenjang_id] : 0;
                                    $total_row_jenjang = $total_merge['total_'.$jenjang_id];
                                    ?>
                                    <th colspan="{{ $total_row_jenjang }}" style="text-align: center;">{{ number_format($get_total_jenjang, 0) }}</th>
                                @endif
                            @endforeach
                            <th rowspan="3" style="text-align: center;">{{ number_format($total_all, 0) }}</th>
                        </tr>
                        <tr class="subtotal">
                            @foreach($list_jenjang as $jenjang_id)
                                @if (isset($list_jenjang_golongan[$jenjang_id]))
                                    @foreach($list_jenjang_golongan[$jenjang_id] as $id)
                                        @if (isset($total_jenjang['total_gol_'.$jenjang_id.'_'.$id]))
                                            <?php
                                            $get_total_jenjang_gol = isset($total_jenjang['total_gol_'.$jenjang_id.'_'.$id]) ? $total_jenjang['total_gol_'.$jenjang_id.'_'.$id] : 0;
                                            $total_all_2 += $get_total_jenjang_gol;
                                            ?>
                                            <th style="text-align: center;">{{ number_format($get_total_jenjang_gol, 0) }}</th>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tr>
                        <tr class="total">
                            <th>Total</th>
                            <th colspan="2" style="text-align: center;">{{ number_format($total_all, 0) }}</th>
                            <th colspan="{{ $total_merge['total'] }}" style="text-align: center;">{{ number_format($total_all_2, 0) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop
