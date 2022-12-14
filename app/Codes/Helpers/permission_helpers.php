<?php
if ( ! function_exists('generateMenu')) {
    function generateMenu() {
        $html = '';
        $adminRole = session()->get('admin_role');
        //dd($adminRole);
        if ($adminRole) {
            $role = \Illuminate\Support\Facades\Cache::remember('role'.$adminRole, env('SESSION_LIFETIME'), function () use ($adminRole) {
                return \App\Codes\Models\Role::where('id', '=', $adminRole)->first();
            });
            if ($role) {
                $permissionRoute = json_decode($role->permission_route, TRUE);
                //dd($permissionRoute);
        $getRoute = \Illuminate\Support\Facades\Route::current()->action['as'];
         // dd($getRoute);
         foreach (listGetPermission(listAllMenu(), $permissionRoute) as $key => $value) {
            $active = '';
            $class = '';
            foreach ($value['active'] as $getActive) {
                if (strpos($getRoute, $getActive) === 0) {
                    $active = ' active';
                }
            }
            if (isset($value['inactive'])) {
                foreach ($value['inactive'] as $getInActive) {
                    if (strpos($getRoute, $getInActive) === 0) {
                        $active = '';
                    }
                }
            }

            if (in_array($value['type'], [2]) && strlen($active) > 0) {
                $class .= ' nav-item has-treeview menu-open';
                $extraLi = '<i class="right fa fa-angle-left"></i>';
            }
            else if (in_array($value['type'], [2])) {
                $class .= ' nav-item has-treeview';
                $extraLi = '<i class="right fa fa-angle-left"></i>';
            }
            else {
                $class .= 'nav-item';
                $extraLi = '';
            }

            if(isset($value['route'])) {
                $route = route($value['route']);
            }
            else {
                $route = '#';
            }

            $getIcon = isset($value['icon']) ? $value['icon'] : '';
            $html .= '<li class="'.$class.'">
            <a href="'.$route.'" title="'.$value['name'].'" class="nav-link'.$active.'">
            '.$getIcon.'
            <p>'.
                $value['title'].$extraLi.'</p></a>';

            if (in_array($value['type'], [2])) {
                $html .= '<ul class="nav nav-treeview">';
                $html .= getMenuChild($value['data'], $getRoute);
                $html .= '</ul>';
            }

            $html .= '</a></li>';
        }
    }
}
        return $html;
    }
}

if ( ! function_exists('getMenuChild')) {
    function getMenuChild($data, $getRoute) {
        $html = '';

        foreach ($data as $value) {
            $active = '';
            foreach ($value['active'] as $getActive) {
                if (strpos($getRoute, $getActive) === 0) {
                    $active = 'active';
                }
            }
            if (isset($value['inactive'])) {
                foreach ($value['inactive'] as $getInActive) {
                    if (strpos($getRoute, $getInActive) === 0) {
                        $active = '';
                    }
                }
            }

            if(isset($value['route'])) {
                $route = route($value['route']);
            }
            else {
                $route = '#';
            }

            $html .= '<li class="nav-item">
                    <a href="'.$route.'" class=" nav-link '.$active.'" title="'.$value['name'].'">
                    <i class="fa fa-circle-o nav-icon"></i><p>'.
                    $value['title'];
            $html .= '</p></a></li>';
        }

        return $html;
    }
}

if ( ! function_exists('getDetailPermission')) {
    function getDetailPermission($module, $permission = ['create' => false,'edit' => false,'show' => false,'destroy' => false]) {
        $adminRole = session()->get('admin_role');
        if ($adminRole) {
            $role = \Illuminate\Support\Facades\Cache::remember('role'.$adminRole, env('SESSION_LIFETIME'), function () use ($adminRole) {
                return \App\Codes\Models\Role::where('id', '=', $adminRole)->first();
            });
            if ($role) {
                $permissionData = json_decode($role->permission_data, TRUE);
                if( isset($permissionData[$module])) {
                    foreach ($permissionData[$module] as $key => $value) {
                        $permission[$key] = true;
                    }
                }
            }
        }
        return $permission;
    }}

if ( ! function_exists('getValidatePermissionMenu')) {
    function getValidatePermissionMenu($permission) {
        $listMenu = [];
        if ($permission) {
            foreach ($permission as $key => $route) {
                if ($key == 'super_admin') {
                    $listMenu['super_admin'] = 1;
                }
                else if ($key == 'role_perancang') {
                    $listMenu['role_perancang'] = 1;
                }
                else if ($key == 'role_atasan') {
                    $listMenu['role_atasan'] = 1;
                }
                else if ($key == 'role_sekretariat') {
                    $listMenu['role_sekretariat'] = 1;
                }
                else if ($key == 'role_tim_penilai') {
                    $listMenu['role_tim_penilai'] = 1;
                }
                else {
                    if (is_array($route)) {
                        foreach ($route as $key2 => $route2) {
                            $listMenu[$key][$key2] = 1;
                        }
                    }
                }
            }
        }
        return $listMenu;
    }
}

if ( ! function_exists('generateListPermission')) {
    function generateListPermission($data = null) {
        $value = isset($data['super_admin']) ? 'checked' : '';
        $html = '<label for="super_admin">
                    <input '.$value.' style="margin-right: 5px;" type="checkbox" class="checkThis super_admin"
                    data-name="super_admin" name="permission[super_admin]" value="1" id="super_admin"/>
                    Super Admin
                </label><br/><br/>';
        $value = isset($data['role_perancang']) ? 'checked' : '';
                $html .= '<label for="role_perancang">
                    <input '.$value.' style="margin-right: 5px;" type="checkbox" class="role_perancang"
                    data-name="role_perancang" name="permission[role_perancang]" value="1" id="role_perancang"/>
                    Perancang
                </label><br/><br/>';

                $value = isset($data['role_atasan']) ? 'checked' : '';
                $html .= '<label for="role_atasan">
                    <input '.$value.' style="margin-right: 5px;" type="checkbox" class="role_atasan"
                    data-name="role_atasan" name="permission[role_atasan]" value="1" id="role_atasan"/>
                    Atasan
                </label><br/><br/>';

                $value = isset($data['role_sekretariat']) ? 'checked' : '';
                $html .= '<label for="role_sekretariat">
                    <input '.$value.' style="margin-right: 5px;" type="checkbox" class="role_sekretariat"
                    data-name="role_sekretariat" name="permission[role_sekretariat]" value="1" id="role_sekretariat"/>
                    Sekretariat
                </label><br/><br/>';

                $value = isset($data['role_tim_penilai']) ? 'checked' : '';
                $html .= '<label for="role_tim_penilai">
                    <input '.$value.' style="margin-right: 5px;" type="checkbox" class="role_tim_penilai"
                    data-name="role_tim_penilai" name="permission[role_tim_penilai]" value="1" id="role_tim_penilai"/>
                    Tim Penilai
                </label><br/><br/>';

        $html .= createTreePermission(listAllMenu(), 0, 'checkThis super_admin', $data);
        return $html;
    }
}

if ( ! function_exists('createTreePermission')) {
    function createTreePermission($data, $left = 0, $class = '', $getData) {
        $html = '';
        foreach ($data as $index => $list) {
            if (in_array($list['type'], [2])) {
                $html .= '<label>'.$list['name'].'</label><br/>';
                $html .= createTreePermission($list['data'], $left + 1, $class, $getData);
            }
            else if (in_array($list['type'], [1])) {
                $value = isset($getData[$list['key']]) ? 'checked' : '';
                $html .= '<label for="checkbox-'.$index.'-'.$list['key'].'">
                            <input '.$value.' style="margin-left: '.($left*30).'px; margin-right: 5px;" type="checkbox"
                            class="'.$class.' '.$list['key'].'" data-name="'.$list['key'].'" name="permission['.$list['key'].']"
                            value="1" id="checkbox-'.$index.'-'.$list['key'].'"/>
                            '.$list['name'].
                    '</label><br/>';
                $html .= getAttributePermission($list['key'], $index, $left + 1, $class.' '.$list['key'], $getData);
                $html .= '<br/>';
            }
        }
        return $html;
    }
}

if ( ! function_exists('getAttributePermission')) {
    function getAttributePermission($module, $index, $left, $class = '', $getData) {
        $html = '';
        $list = listAvailablePermission();
        if (isset($list[$module])) {
            foreach ($list[$module] as $key => $value) {
                $value = isset($getData[$module][$key]) ? 'checked' : '';
                $html .= '<label for="checkbox-'.$index.'-'.$module.'-'.$key.'">
                            <input '.$value.' style="margin-left: '.($left*30).'px; margin-right: 5px;" type="checkbox"
                            class="'.$class.'" name="permission['.$module.']['.$key.']" value="1"
                            id="checkbox-'.$index.'-'.$module.'-'.$key.'"/>
                            '.$key.
                        '</label><br/>';
            }
        }
        return $html;
    }
}

if ( ! function_exists('getPermissionRouteList')) {
    function getPermissionRouteList($listMenu) {
        $listAllowed = [];
        $listPermission = listAvailablePermission();
        foreach ($listPermission as $key => $list) {
            if (in_array($key, ['super_admin', 'super_admin', 'super_admin', 'super_admin', 'super_admin']))
                continue;
            foreach ($list as $key2 => $listRoute) {
                if (isset($listMenu[$key][$key2])) {
                    foreach ($listRoute as $value) {
                        $listAllowed[] = $value;
                    }
                }
            }
        }
        return $listAllowed;
    }
}

if ( ! function_exists('listGetPermission')) {
    function listGetPermission($listMenu, $permissionRoute)
    {
        $result = [];
        if ($permissionRoute) {
            foreach ($listMenu as $list) {
                if ($list['type'] == 1) {
                    if (in_array($list['route'], $permissionRoute)) {
                        $result[] = $list;
                    }
                }
                else {
                    $getResult = listGetPermission($list['data'], $permissionRoute);
                    if (count($getResult) > 0) {
                        $list['data'] = $getResult;
                        $result[] = $list;
                    }
                }
            }
        }

        return $result;
    }
}

if ( ! function_exists('listAllMenu')) {
    function listAllMenu()
    {
        return [

            //KEGIATAN
            [
                'name' => __('general.kegiatan'),
                'icon' => '<i class="nav-icon fa fa-book"></i>',
                'title' => __('general.kegiatan'),
                'active' => [
                    'admin.kegiatan.',
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.kegiatan'),
                        'title' => __('general.kegiatan'),
                        'active' => ['admin.kegiatan.'],
                        'route' => 'admin.kegiatan.index',
                        'key' => 'kegiatan',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.create_kegiatan'),
                        'title' => __('general.create_kegiatan'),
                        'active' => ['admin.kegiatan.create'],
                        'route' => 'admin.kegiatan.create',
                        'key' => 'kegiatan',
                        'type' => 1
                    ]
                ]
            ],
             //Persetujuan Mutakhir
             [
                'name' => __('general.pemuktahiran'),
                'icon' => '<i class="nav-icon fa fa-edit"></i>',
                'title' => __('general.pemuktahiran'),
                'active' => [
                    'admin.pemuktahiran-data-diri.',
                    'admin.persetujuan-pemuktahiran.',
                    'admin.pemuktahiran-ak.'
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.data-diri'),
                        'title' => __('general.data-diri'),
                        'active' => ['admin.pemuktahiran-data-diri.'],
                        'route' => 'admin.pemuktahiran-data-diri.index',
                        'key' => 'pemuktahiran-data-diri',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.persetujuan_pemuktahiran'),
                        'title' => __('general.persetujuan_pemuktahiran'),
                        'active' => ['admin.persetujuan-pemuktahiran.'],
                        'route' => 'admin.persetujuan-pemuktahiran.index',
                        'key' => 'persetujuan-pemuktahiran',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.pemuktahiran-ak'),
                        'title' => __('general.pemuktahiran-ak'),
                        'active' => ['admin.pemuktahiran-ak'],
                        'route' => 'admin.pemuktahiran-ak.index',
                        'key' => 'pemuktahiran-ak',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.persetujuan_pemuktahiran_ak'),
                        'title' => __('general.persetujuan_pemuktahiran_ak'),
                        'active' => ['admin.persetujuan-pemuktahiran-ak.'],
                        'route' => 'admin.persetujuan-pemuktahiran-ak.index',
                        'key' => 'persetujuan-pemuktahiran-ak',
                        'type' => 1
                    ]
                ]
            ],
            // Persetujuan Permuktahiran

            //Surat Pernyataan
            [
                'name' => __('general.surat_pernyataan'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.surat_pernyataan'),
                'active' => [
                    'admin.surat-pernyataan.'
                ],
                'route' => 'admin.surat-pernyataan.index',
                'key' => 'surat-pernyataan',
                'type' => 1,
            ],
            [
                'name' => __('general.persetujuan_surat_pernyataan'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.persetujuan_surat_pernyataan'),
                'active' => [
                    'admin.persetujuan-surat-pernyataan.index'
                ],
                'route' => 'admin.persetujuan-surat-pernyataan.index',
                'key' => 'persetujuan-surat-pernyataan',
                'type' => 1,
            ],

            // DUPAK
            [
                'name' => __('general.dupak'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.dupak'),
                'active' => [
                    'admin.dupak.'
                ],
                'route' => 'admin.dupak.index',
                'key' => 'dupak',
                'type' => 1,
            ],
            [
                'name' => __('general.persetujuan_dupak'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.persetujuan_dupak'),
                'active' => [
                    'admin.persetujuan-dupak.index'
                ],
                'route' => 'admin.persetujuan-dupak.index',
                'key' => 'persetujuan-dupak',
                'type' => 1,
            ],

            //PAK
            [
                'name' => __('general.pak'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.pak'),
                'active' => [
                    'admin.pak.'
                ],
                'route' => 'admin.pak.index',
                'key' => 'pak',
                'type' => 1,
            ],
            [
                'name' => __('general.persetujuan_pak'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.persetujuan_pak'),
                'active' => [
                    'admin.persetujuan-pak.index'
                ],
                'route' => 'admin.persetujuan-pak.index',
                'key' => 'persetujuan-pak',
                'type' => 1,
            ],

            //PERMEN
            [
                'name' => __('general.permen'),
                'icon' => '<i class="nav-icon fa fa-book"></i>',
                'title' => __('general.permen'),
                'active' => [
                    'admin.permen.',
                    'admin.ms-kegiatan.'
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.permen'),
                        'title' => __('general.permen'),
                        'active' => ['admin.permen.'],
                        'route' => 'admin.permen.index',
                        'key' => 'permen',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.ms_kegiatan'),
                        'title' => __('general.ms_kegiatan'),
                        'active' => ['admin.ms-kegiatan.'],
                        'route' => 'admin.ms-kegiatan.index',
                        'key' => 'ms-kegiatan',
                        'type' => 1
                    ]

                ]
            ],
            //STAF
            [
                'name' => __('general.staff'),
                'icon' => '<i class="nav-icon fa fa-address-card"></i>',
                'title' => __('general.staff'),
                'active' => [
                    'admin.staff.',
                    'admin.atasan.',
                    'admin.perancang.',
                    'admin.sekretariat.',
                    'admin.tim_penilai.',
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.perancang'),
                        'title' => __('general.perancang'),
                        'active' => ['admin.perancang.'],
                        'route' => 'admin.perancang.index',
                        'key' => 'perancang',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.atasan'),
                        'title' => __('general.atasan'),
                        'active' => ['admin.atasan.'],
                        'route' => 'admin.atasan.index',
                        'key' => 'atasan',
                        'type' => 1
                    ],

                    [
                       'name' => __('general.sekretariat'),
                        'title' => __('general.sekretariat'),
                        'active' => ['admin.sekretariat.'],
                       'route' => 'admin.sekretariat.index',
                        'key' => 'sekretariat',
                        'type' => 1
                    ],
                   [
                        'name' => __('general.tim_penilai'),
                        'title' => __('general.tim_penilai'),
                        'active' => ['admin.tim_penilai.'],
                        'route' => 'admin.tim_penilai.index',
                        'key' => 'tim_penilai',
                        'type' => 1
                    ],
                ]
            ],
            //SETTING
            [
                'name' => __('general.setting'),
                'icon' => '<i class="nav-icon fa fa-gear"></i>',
                'title' => __('general.setting'),
                'active' => [
                    'admin.role.',
                    'admin.admin.',
                    'admin.golongan.',
                    'admin.pangkat.',
                    'admin.unit-kerja.',
                    'admin.pendidikan.',
                    'admin.jenjang-perancang.',
                    'admin.instansi.',
                    'admin.pangkat  .'
                ],

                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.role'),
                        'title' => __('general.role'),
                        'active' => ['admin.role.'],
                        'route' => 'admin.role.index',
                        'key' => 'role',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.admin'),
                        'title' => __('general.admin'),
                        'active' => ['admin.admin.'],
                        'route' => 'admin.admin.index',
                        'key' => 'admin',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.golongan'),
                        'icon' => '<i class="nav-icon fa fa-users"></i>',
                        'title' => __('general.golongan'),
                        'active' => ['admin.golongan.'],
                        'route' => 'admin.golongan.index',
                        'key' => 'golongan',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.pangkat'),
                        'icon' => '<i class="nav-icon fa fa-users"></i>',
                        'title' => __('general.pangkat'),
                        'active' => ['admin.pangkat.'],
                        'route' => 'admin.pangkat.index',
                        'key' => 'pangkat',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.unit_kerja'),
                        'icon' => '<i class="nav-icon fa fa-university"></i>',
                        'title' => __('general.unit_kerja'),
                        'active' => ['admin.unit-kerja.'],
                        'route' => 'admin.unit-kerja.index',
                        'key' => 'unit-kerja',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.jenjang-perancang'),
                        'icon' => '<i class="nav-icon fa fa-server"></i>',
                        'title' => __('general.jenjang-perancang'),
                        'active' => ['admin.jenjang-perancang.'],
                        'route' => 'admin.jenjang-perancang.index',
                        'key' => 'jenjang-perancang',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.instansi'),
                        'icon' => '<i class="nav-icon fa fa-users"></i>',
                        'title' => __('general.instansi'),
                        'active' => ['admin.instansi.'],
                        'route' => 'admin.instansi.index',
                        'key' => 'instansi',
                        'type' => 1
                    ],

                ]
            ],
        ];
    }
}

if ( ! function_exists('listAvailablePermission'))
{
    function listAvailablePermission() {
        $listPermission = [];

        foreach ([
            'permen',
            'mskegiatan',
            'ms-kegiatan',
            'kegiatan',
            'perancang',
            'atasan',
            'sekretariat',
            'tim_penilai',
            'instansi',
            'pangkat',
            'golongan',
            'jenjang-perancang',
            'pendidikan',
            'unit-kerja',
            'role',
            'admin'
                 ] as $keyPermission) {
            $listPermission[$keyPermission] = [
                'list' => [
                    'admin.'.$keyPermission.'.index',
                    'admin.'.$keyPermission.'.dataTable'
                ],
                'create' => [
                    'admin.'.$keyPermission.'.create',
                    'admin.'.$keyPermission.'.store'
                ],
                'edit' => [
                    'admin.'.$keyPermission.'.edit',
                    'admin.'.$keyPermission.'.update'
                ],
                'show' => [
                    'admin.'.$keyPermission.'.show'
                ],
//                'destroy' => [
//                    'admin.'.$keyPermission.'.destroy'
//                ]
            ];
        }

        foreach ([
            'pemuktahiran-data-diri',
            'pemuktahiran-ak',
                 ] as $keyPermission) {
            $listPermission[$keyPermission] = [
                'list' => [
                    'admin.'.$keyPermission.'.index',
                    'admin.'.$keyPermission.'.dataTable'
                ],
                'create' => [
                    'admin.'.$keyPermission.'.create',
                    'admin.'.$keyPermission.'.store'
                ],

                'show' => [
                    'admin.'.$keyPermission.'.show'
                ],
            ];
        }

        foreach ([
            'persetujuan-surat-pernyataan',
            'persetujuan-pemuktahiran',
            'persetujuan-pemuktahiran-ak',
            'dupak',
            'persetujuan-dupak',
            'pak',
            'persetujuan-pak',
                 ] as $keyPermission) {
            $listPermission[$keyPermission] = [
                'list' => [
                    'admin.'.$keyPermission.'.index',
                    'admin.'.$keyPermission.'.dataTable'
                ],
                'edit' => [
                    'admin.'.$keyPermission.'.edit',
                    'admin.'.$keyPermission.'.update'
                ],
                'show' => [
                    'admin.'.$keyPermission.'.show',
                    'admin.'.$keyPermission.'.showPdf',
                    'admin.'.$keyPermission.'.showDupakPdf'
                ],
            ];
        }

        foreach ([
            'surat-pernyataan',
                 ] as $keyPermission) {
            $listPermission[$keyPermission] = [
                'list' => [
                    'admin.'.$keyPermission.'.index',
                    'admin.'.$keyPermission.'.dataTable'
                ],
                'show' => [
                    'admin.'.$keyPermission.'.show',
                    'admin.'.$keyPermission.'.showPdf',
                    'admin.'.$keyPermission.'.showDupakPdf'
                ],
            ];
        }

        $listPermission['admin']['edit'][] = 'admin.admin.password';
        $listPermission['admin']['edit'][] = 'admin.admin.updatePassword';

        $listPermission['kegiatan']['create'][] = 'admin.kegiatan.submitKegiatan';
        $listPermission['kegiatan']['create'][] = 'admin.kegiatan.storeSubmitKegiatan';

        $listPermission['persetujuan-pemuktahiran']['edit'][] = 'admin.persetujuan-pemuktahiran.approve';
        $listPermission['persetujuan-pemuktahiran']['edit'][] = 'admin.persetujuan-pemuktahiran.reject';

        $listPermission['persetujuan-pemuktahiran-ak']['edit'][] = 'admin.persetujuan-pemuktahiran-ak.approve';
        $listPermission['persetujuan-pemuktahiran-ak']['edit'][] = 'admin.persetujuan-pemuktahiran-ak.reject';



        $listPermission['permen']['list'][] = 'admin.mskegiatan.index';
        $listPermission['permen']['list'][] = 'admin.mskegiatan.dataTable';
        $listPermission['permen']['edit'][] = 'admin.mskegiatan.edit';
        $listPermission['permen']['edit'][] = 'admin.mskegiatan.update';
        $listPermission['permen']['create'][] = 'admin.mskegiatan.create';
        $listPermission['permen']['create'][] = 'admin.mskegiatan.store';
        $listPermission['permen']['show'][] = 'admin.mskegiatan.show';
        $listPermission['permen']['destroy'][] = 'admin.mskegiatan.destroy';


        return $listPermission;
    }
}
