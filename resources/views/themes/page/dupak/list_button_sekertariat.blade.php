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
   title="@lang('general.lihat_kegiatan')">
    <i class="fa fa-eye"></i>
    <span class="d-none d-md-inline"> @lang('general.lihat_kegiatan')</span>
</a>
@if(in_array($query->approved, [0]))
<a href="{{ route('admin.' . $thisRoute . '.approve', $query->{$masterId}) }}" class="mb-1 btn btn-success btn-sm"
   title="@lang('general.approved')">
    <i class="fa fa-check-square"></i>
    <span class="d-none d-md-inline"> @lang('general.approved')</span>
</a>
<a href="{{ route('admin.' . $thisRoute . '.reject', $query->{$masterId}) }}" class="mb-1 btn btn-danger btn-sm"
   title="@lang('general.reject')">
    <i class="fa fa-close"></i>
    <span class="d-none d-md-inline"> @lang('general.reject')</span>
</a>
@endif
