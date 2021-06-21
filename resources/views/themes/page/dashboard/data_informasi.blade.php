
@extends(env('ADMIN_TEMPLATE').'._base.layout')

@section('title', __('general.data dan informasi'))

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.profile') }}" class="brand-link logo-switch">
        <span class="brand-image-xl logo-xs">{{ substr(env('WEBSITE_NAME'), 0, 2) }}</span>
        <span class="brand-image-xs logo-xl">{{ env('WEBSITE_NAME') }}</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.profile') }}"
                       class="nav-link{{ in_array(Route::current()->action['as'], ['admin.profile', 'admin.getProfile', 'admin.getPassword']) ? ' active' : '' }}"
                       title="@lang('general.profile')">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            @lang('general.profile')
                        </p>
                    </a>
                </li>
                @if(session()->get(env('APP_NAME').'admin_perancang'))
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.kegiatan.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.kegiatan.') === 0 ? ' active' : '' }}"
                           title="@lang('general.kegiatan')">
                            <i class="nav-icon fa fa-book"></i>
                            <p>
                                @lang('general.kegiatan')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.kegiatan.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.kegiatan.') === 0 && Route::current()->action['as'] != 'admin.kegiatan.create' ? ' active' : '' }}"
                                   title="@lang('general.kegiatan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.kegiatan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.kegiatan.create') }}"
                                   class="nav-link{{ Route::current()->action['as'] == 'admin.kegiatan.create' ? ' active' : '' }}"
                                   title="@lang('general.create_kegiatan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_kegiatan')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(session()->get(env('APP_NAME').'admin_perancang') || session()->get(env('APP_NAME').'admin_atasan'))
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 ? ' active' : '' }}"
                           title="@lang('general.surat_pernyataan')">
                            <i class="nav-icon fa fa-envelope"></i>
                            <p>
                                @lang('general.surat_pernyataan')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(session()->get(env('APP_NAME').'admin_perancang'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.surat-pernyataan.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.surat-pernyataan.') === 0 && (!(Route::current()->action['as'] == 'admin.surat-pernyataan.create' || Route::current()->action['as'] == 'admin.surat-pernyataan.verification')) ? ' active' : '' }}"
                                       title="@lang('general.surat_pernyataan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.surat_pernyataan')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.surat-pernyataan.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.create' ? ' active' : '' }}"
                                       title="@lang('general.create_surat_pernyataan')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_surat_pernyataan')</p>
                                    </a>
                                </li>
                            @endif
                            @if(session()->get(env('APP_NAME').'admin_atasan'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.surat-pernyataan.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.surat-pernyataan.verification' ? ' active' : '' }}"
                                       title="@lang('general.verification')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(session()->get(env('APP_NAME').'admin_perancang') || session()->get(env('APP_NAME').'admin_sekretariat'))
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 ? ' active' : '' }}"
                           title="@lang('general.dupak')">
                            <i class="nav-icon fa fa-briefcase"></i>
                            <p>
                                @lang('general.dupak')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(session()->get(env('APP_NAME').'admin_perancang'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.index') }}"
                                       class="nav-link{{ strpos(Route::current()->action['as'], 'admin.dupak.') === 0 && (!(Route::current()->action['as'] == 'admin.dupak.create' || Route::current()->action['as'] == 'admin.dupak.verification')) ? ' active' : '' }}"
                                       title="@lang('general.dupak')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.dupak')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.create' ? ' active' : '' }}"
                                       title="@lang('general.create_dupak')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_dupak')</p>
                                    </a>
                                </li>
                            @endif
                            @if(session()->get(env('APP_NAME').'admin_sekretariat'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.dupak.verification') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.dupak.verification' ? ' active' : '' }}"
                                       title="@lang('general.verification')">
                                        <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.verification')</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(session()->get(env('APP_NAME').'admin_tim_penilai'))
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 ? ' active' : '' }}"
                           title="@lang('general.bapak')">
                            <i class="nav-icon fa fa-newspaper-o"></i>
                            <p>
                                @lang('general.bapak')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.bapak.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.bapak.') === 0 && Route::current()->action['as'] != 'admin.bapak.create' ? ' active' : '' }}"
                                   title="@lang('general.bapak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.bapak')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bapak.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.bapak.create' ? ' active' : '' }}"
                                   title="@lang('general.create_bapak')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_bapak')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(session()->get(env('APP_NAME').'admin_super_admin'))
                    <li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.') === 0 ? ' active' : '' }}"
                           title="@lang('general.staff')">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                @lang('general.staff')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.index') }}"
                                   class="nav-link{{ Route::current()->action['as'] == 'admin.staff.index' ? ' active' : '' }}"
                                   title="@lang('general.staff')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.staff')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexAtasan') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexAtasan') === 0 ? ' active' : '' }}"
                                   title="@lang('general.atasan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.atasan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexPerancang') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexPerancang') === 0 ? ' active' : '' }}"
                                   title="@lang('general.perancang')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.perancang')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexCalonPerancang') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexCalonPerancang') === 0 ? ' active' : '' }}"
                                   title="@lang('general.calon_perancang')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.calon_perancang')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexSekretariat') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexSekretariat') === 0 ? ' active' : '' }}"
                                   title="@lang('general.sekretariat')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.sekretariat')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.indexTimPenilai') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.staff.indexTimPenilai') === 0 ? ' active' : '' }}"
                                   title="@lang('general.tim_penilai')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.tim_penilai')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.staff.create') }}" class="nav-link{{ Route::current()->action['as'] == 'admin.staff.create' ? ' active' : '' }}"
                                   title="@lang('general.create_staff')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.create_staff')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
{{--     --}}
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.logout') }}" class="nav-link">
                        <i class="nav-icon fa fa-power-off"></i>
                        <p>
                            @lang('general.logout')
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
