<li class="nav-item{{ strpos(Route::current()->action['as'], 'admin.gender.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.permen.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.user-registered.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.golongan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jabatan-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jenjang-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.ms-kegiatan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.pendidikan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.unit-kerja.') === 0
                            ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ strpos(Route::current()->action['as'], 'admin.gender.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.permen.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.golongan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jabatan-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.jenjang-perancang.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.ms-kegiatan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.pendidikan.') === 0 ||
                            strpos(Route::current()->action['as'], 'admin.unit-kerja.') === 0
                            ? ' active' : '' }}"
                           title="@lang('general.setting')">
                            <i class="nav-icon fa fa-gear"></i>
                            <p>
                                @lang('general.setting')
                            </p>
                            <i class="right fa fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.permen.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.permen.index') === 0 ? ' active' : '' }}"
                                   title="@lang('Peraturan Mentri')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('Peraturan Mentri')</p>
                                </a>
                                <a href="{{ route('admin.user-registered.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.user-registered.index') === 0 ? ' active' : '' }}"
                                   title="@lang('Pengguna Terdaftar')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('Pengguna Terdaftar')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.gender.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.gender.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.gender')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.gender')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.golongan.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.golongan.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.golongan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.golongan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.jabatan-perancang.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.jabatan-perancang.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.jabatan_perancang')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.jabatan_perancang')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.jenjang-perancang.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.jenjang-perancang.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.jenjang_perancang')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.jenjang_perancang')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ms-kegiatan.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.ms-kegiatan.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.ms_kegiatan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.ms_kegiatan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pendidikan.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.pendidikan.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.pendidikan')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.pendidikan')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.unit-kerja.index') }}"
                                   class="nav-link{{ strpos(Route::current()->action['as'], 'admin.unit-kerja.index') === 0 ? ' active' : '' }}"
                                   title="@lang('general.unit_kerja')">
                                    <i class="fa fa-circle-o nav-icon"></i><p>@lang('general.unit_kerja')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.report') }}" class="nav-link">
                            <i class="nav-icon fa fa-print"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
