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
@if ($getFlag == 1)
    <a href="{{ route('admin.' . $thisRoute . '.cetakKegiatan', $query->{$masterId}) }}" class="mb-1 btn btn-info btn-sm"
       title="@lang('general.lihat_kegiatan')">
        <i class="fa fa-eye"></i>
        <span class="d-none d-md-inline"> @lang('general.lihat_kegiatan')</span>
    </a>
    @if ($query->send_status == 1)
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
    @if ($query->approved == 1)
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

@else
    @if($query->approved == 0 && session()->get(env('APP_NAME').'admin_perancang') == 1)
        <a href="{{ route('admin.' . $thisRoute . '.edit', $query->{$masterId}) }}" class="mb-1 btn btn-primary btn-sm"
           title="@lang('general.edit')">
            <i class="fa fa-pencil"></i>
            <span class="d-none d-md-inline"> @lang('general.edit')</span>
        </a>
    @endif
    @if($query->status_upload == 1 && $query->send_status == 0)
        <a href="#" class="mb-1 btn btn-success btn-sm" title="@lang('Kirim')"
           onclick="return sendToSekretariat('{{ route('admin.' . $thisRoute . '.sendToSekretariat', $query->{$masterId}) }}', 'post', 1)">
            <i class="fa fa-paper-plane"></i>
            <span class="d-none d-md-inline"> @lang('Kirim')</span>
        </a>
    @endif
    @if($query->send_status == 1)
        <a href="#" class="mb-1 btn btn-success btn-sm disabled" title="@lang('Terkirim')">
            <i class="fa fa-paper-plane"></i>
            <span class="d-none d-md-inline"> @lang('Terkirim')</span>
        </a>
    @endif
    <!-- Button trigger modal -->
    <button type="button" class="mb-1 btn btn-primary btn-sm" onclick="showUploadFile('{{route('admin.dupak.uploadFile', $query->{$masterId})}}')">
        <i class="fa fa-upload"></i>
        Unggah File
    </button>
@endif
