@if ($permission['edit'])
    <a href="{{ route('admin.' . $thisRoute . '.show', $query->{$masterId}) }}" class="mb-1 btn btn-primary btn-sm"
       title="@lang('general.detail')">
        <i class="fa fa-eye"></i>
        <span class="d-none d-md-inline"> @lang('general.detail')</span>
    </a>

    @if($query->status == 0)
        <a href="{{ route('admin.' . $thisRoute . '.rejected', $query->{$masterId}) }}" class="mb-1 btn btn-danger btn-sm"
           title="@lang('general.rejected')">
            <i class="fa fa-window-close"></i>
            <span class="d-none d-md-inline"> @lang('general.rejected')</span>
        </a>
        <a href="{{ route('admin.' . $thisRoute . '.approved', $query->{$masterId}) }}" class="mb-1 btn btn-success btn-sm"
           title="@lang('general.approved')">
            <i class="fa fa-check"></i>
            <span class="d-none d-md-inline"> @lang('general.approved')</span>
        </a>

    @elseif($query->status == 2)
    <a href="{{ route('admin.' . $thisRoute . '.approved', $query->{$masterId}) }}" class="mb-1 btn btn-success btn-sm"
       title="@lang('general.approved')" style="display: none">
        <i class="fa fa-check"></i>
        <span class="d-none d-md-inline"> @lang('general.approved')</span>
    </a>
        <a href="{{ route('admin.' . $thisRoute . '.rejected', $query->{$masterId}) }}" class="mb-1 btn btn-danger btn-sm"
           title="@lang('general.rejected')" style="display: none">
            <i class="fa fa-window-close"></i>
            <span class="d-none d-md-inline"> @lang('general.rejected')</span>
        </a>

@endif
@endif
@if ($query->status == 1 && $query->create_staff_status != 1)
    <a href="{{ route('admin.' . $thisRoute . '.createStaff', $query->{$masterId}) }}" class="mb-1 btn btn-primary btn-sm"
       title="@lang('Buat Staff')">
        <i class="fa fa-plus"></i>
        <span class="d-none d-md-inline"> @lang('Buat Staff')</span>
    </a>
@endif

@if ($query->status == 1 && $query->create_staff_status == 1)
    <a href="{{ route('admin.' . $thisRoute . '.createStaff', $query->{$masterId}) }}" class="mb-1 btn btn-primary btn-sm"
       title="@lang('Buat Staff')"style="display: none">
        <i class="fa fa-plus"></i>
        <span class="d-none d-md-inline"> @lang('Buat Staff')</span>
    </a>
@endif




