@extends(env('ADMIN_TEMPLATE').'._base.full')

@section('title', __('Data Perancang'))

@section('content')
    <section class="content" id="fullpage">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Data Perancang
                    </h3>
                </div>
                <div class="card-body table-responsive">
                    <p>&nbsp;</p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th rowspan="2">Unit Kerja</th>
                            <th colspan="2">Jenis Kelamin</th>
                            <th rowspan="2">JENJANG JABATAN FUNGSIONAL</th>
                            <th rowspan="2">Total</th>
                        </tr>
                        <tr>
                            <th>L</th>
                            <th>P</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $list)
                            <tr>
                                <td>{{ $list['unit_kerja_name'] }}</td>
                                <td>{{ number_format($list['pria'], 0) }}</td>
                                <td>{{ number_format($list['wanita'], 0) }}</td>
                                <td>{{ number_format($list['total'], 0) }}</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th>{{ number_format($totalData, 0) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <footer class="text-center">2021 &copy; PAK</footer>
                </div>
            </div>
        </div>
    </section>

@stop
