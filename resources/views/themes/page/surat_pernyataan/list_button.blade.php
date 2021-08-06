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
@if ($query->approved == 0 && session()->get(env('APP_NAME').'admin_perancang') == 1)
    <a href="{{ route('admin.' . $thisRoute . '.edit', $query->{$masterId}) }}" class="mb-1 btn btn-primary btn-sm"
       title="@lang('general.edit')">
        <i class="fa fa-pencil"></i>
        <span class="d-none d-md-inline"> @lang('general.edit')</span>
    </a>
    <?php /*
    <a href="#" class="mb-1 btn btn-danger btn-sm" title="@lang('general.delete')"
       onclick="return actionData('{{ route('admin.' . $thisRoute . '.destroy', $query->{$masterId}) }}', 'delete')">
        <i class="fa fa-trash"></i>
        <span class="d-none d-md-inline"> @lang('general.delete')</span>
    </a> */ ?>
@endif
