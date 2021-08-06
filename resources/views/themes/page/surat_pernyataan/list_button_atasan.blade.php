@if (strlen($query->pdf_url) > 5)
<a href="{{ asset($query->pdf_url) }}" target="_blank" class="mb-1 btn btn-info btn-sm"
   title="@lang('general.print')">
    <i class="fa fa-print"></i>
    <span class="d-none d-md-inline"> @lang('general.print')</span>
</a>
@endif
<a href="{{ route('admin.' . $thisRoute . '.show', $query->{$masterId}) }}" class="mb-1 btn btn-info btn-sm"
   title="@lang('general.show')">
    <i class="fa fa-eye"></i>
    <span class="d-none d-md-inline"> @lang('general.show')</span>
</a>
@if($query->approved == 0 && session()->get(env('APP_NAME').'admin_atasan') == 1)
    <a href="#" class="mb-1 btn btn-success btn-sm" title="@lang('Disetujui')"
       onclick="return actionData2('{{ route('admin.' . $thisRoute . '.approve', $query->{$masterId}) }}', 'post', 1)">
        <i class="fa fa-check-circle"></i>
        <span class="d-none d-md-inline"> @lang('Disetujui')</span>
    </a>
    <a href="#" class="mb-1 btn btn-warning btn-sm" title="@lang('Menolak')"
       onclick="return actionData2('{{ route('admin.' . $thisRoute . '.reject', $query->{$masterId}) }}', 'post', 2)">
        <i class="fa fa-times-circle"></i>
        <span class="d-none d-md-inline"> @lang('Menolak')</span>
    </a>
@endif
