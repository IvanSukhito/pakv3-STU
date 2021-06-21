@if (strlen($query->pdf_url) > 5)
    <a href="{{ asset($query->pdf_url) }}" target="_blank" class="mb-1 btn btn-info btn-sm"
       title="@lang('general.print') BAPAK">
        <i class="fa fa-print"></i>
        <span class="d-none d-md-inline"> @lang('general.print') BAPAK</span>
    </a>
@endif
@if (strlen($query->pak_pdf_url) > 5)
    <a href="{{ asset($query->pak_pdf_url) }}" target="_blank" class="mb-1 btn btn-info btn-sm"
       title="@lang('general.print') PAK">
        <i class="fa fa-print"></i>
        <span class="d-none d-md-inline"> @lang('general.print') PAK</span>
    </a>
@endif
