@if ($permission['edit'])
    <a href="{{ route('admin.' . $thisRoute . '.edit', $query->{$masterId}) }}" class="mb-1 btn btn-primary btn-sm"
       title="@lang('general.edit')">
        <i class="fa fa-pencil"></i>
        <span class="d-none d-md-inline"> @lang('general.edit')</span>
    </a>
    <a href="{{ route('admin.mskegiatan.index', $query->{$masterId})}}" class="mb-1 btn btn-primary btn-sm"
       title="@lang('Master Kegiatan')">
        <i class="fa fa-list"></i>
        <span class="d-none d-md-inline"> @lang('Master Kegiatan')</span>
    </a>
    @endif
    @if ($permission['destroy'])
    <a href="#" class="mb-1 btn btn-danger btn-sm" title="@lang('general.delete')"
       onclick="return actionData('{{ route('admin.' . $thisRoute . '.destroy', $query->{$masterId}) }}', 'delete')">
        <i class="fa fa-trash"></i>
        <span class="d-none d-md-inline"> @lang('general.delete')</span>
    </a>
@endif


