@if (strlen($query->pdf_url) > 5)
<a href="{{ asset($query->pdf_url) }}" target="_blank" class="mb-1 btn btn-info btn-sm"
   title="@lang('general.print')">
    <i class="fa fa-print"></i>
    <span class="d-none d-md-inline"> @lang('general.print')</span>
</a>
@else
    <a href="{{ route('admin.' . $thisRoute . '.previewPDF', $query->{$masterId}) }}" target="_blank" class="mb-1 btn btn-info btn-sm"
       title="@lang('general.preview_pdf')">
        <i class="fa fa-print"></i>
        <span class="d-none d-md-inline"> @lang('general.preview_pdf')</span>
    </a>
@endif
<a href="{{ route('admin.' . $thisRoute . '.show', $query->{$masterId}) }}" class="mb-1 btn btn-info btn-sm"
   title="@lang('general.show')">
    <i class="fa fa-eye"></i>
    <span class="d-none d-md-inline"> @lang('general.show')</span>
</a>
<a href="{{ route('admin.' . $thisRoute . '.cetakKegiatan', $query->{$masterId}) }}" class="mb-1 btn btn-info btn-sm"
   title="@lang('Lihat Kegiatan')">
    <i class="fa fa-print"></i>
    <span class="d-none d-md-inline"> @lang('Lihat Kegiatan')</span>
</a>
@if($query->approved == 0 || session()->get(env('APP_NAME').'admin_sekretariat'))
    <a href="{{ route('admin.' . $thisRoute . '.approve', $query->{$masterId}) }}" class="mb-1 btn btn-success btn-sm" title="@lang('Disetujui')">
        <i class="fa fa-check-circle"></i>
        <span class="d-none d-md-inline"> @lang('Disetujui')</span>
    </a>
    <a href="{{ route('admin.' . $thisRoute . '.reject', $query->{$masterId}) }}" class="mb-1 btn btn-warning btn-sm" title="@lang('Menolak')">
        <i class="fa fa-times-circle"></i>
        <span class="d-none d-md-inline"> @lang('Menolak')</span>
    </a>
@endif

{{--@if($query->approved == 1 || session()->get(env('APP_NAME').'admin_sekretariat'))--}}
{{--    <a href="{{ route('admin.' . $thisRoute . '.approve', $query->{$masterId}) }}" class="mb-1 btn btn-success btn-sm" title="@lang('Disetujui')">--}}
{{--        <i class="fa fa-check-circle"></i>--}}
{{--        <span class="d-none d-md-inline"> @lang('Disetujui')</span>--}}
{{--    </a>--}}
{{--    <a href="{{ route('admin.' . $thisRoute . '.reject', $query->{$masterId}) }}" class="mb-1 btn btn-warning btn-sm" title="@lang('Menolak')">--}}
{{--        <i class="fa fa-times-circle"></i>--}}
{{--        <span class="d-none d-md-inline"> @lang('Menolak')</span>--}}
{{--    </a>--}}
{{--@endif--}}
