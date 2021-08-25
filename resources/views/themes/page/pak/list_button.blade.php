@if ($permission['show'])
    <a href="{{ route('admin.' . $thisRoute . '.show', $query->{$masterId}) }}" class="mb-1 btn btn-info btn-sm"
       title="@lang('general.show')">
        <i class="fa fa-eye"></i>
        <span class="d-none d-md-inline"> @lang('general.show')</span>
    </a>
    <a href="/pakPdf"  name="pdf" value="1" class="mb-1 btn btn-primary btn-sm" title="@lang('general.download_pdf')">
        <i class="fa fa-download"></i><span class=""> @lang('general.download_pak')</span>
    </a>
@endif
@if ($permission['destroy'])
    <a href="#" class="mb-1 btn btn-danger btn-sm" title="@lang('general.delete')"
       onclick="return actionData('{{ route('admin.' . $thisRoute . '.destroy', $query->{$masterId}) }}', 'delete')">
        <i class="fa fa-trash"></i>
        <span class="d-none d-md-inline"> @lang('general.delete')</span>
    </a>
@endif
@if ($permission['edit']  && in_array($query->status, [1,2]) )
<a href="{{ route('admin.' . $thisRoute . '.edit', $query->{$masterId}) }}" class="mb-1 btn btn-success btn-sm"
       title="@lang('general.upload_sp')">
        <i class="fa fa-plus"></i>
        <span class="d-none d-md-inline"> @lang('general.upload_sp')</span>
</a>
@endif
