<?php
if ( ! function_exists('generateMenu')) {
    function generateMenu() {
        $html = '';
        $getRoute = \Illuminate\Support\Facades\Route::current()->action['as'];
        foreach (listGetPermission(listAllMenu(), []) as $key => $value) {
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
        $adminRole = session()->get(env('APP_NAME').'admin_role');
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
    }
}

if ( ! function_exists('getValidatePermissionMenu')) {
    function getValidatePermissionMenu($permission) {
        $listMenu = [];
        if ($permission) {
            foreach ($permission as $key => $route) {
                if ($key == 'super_admin') {
                    $listMenu['super_admin'] = 1;
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
            if ($key == 'super_admin')
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
        foreach ($listMenu as $list) {
            if (in_array($list['type'], [1,3])) {
                $result[] = $list;
            }
            else {
                $getResult = listGetPermission($list['data'], $permissionRoute);
                if (count($getResult) > 0) {
                    $list['data'] = $getResult;
                    $result[] = $list;
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
            [
                'name' => __('general.kegiatan'),
                'icon' => '<i class="nav-icon fa fa-book"></i>',
                'title' => __('general.kegiatan'),
                'active' => [
                    'admin.kegiatan.'
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.kegiatan'),
                        'title' => __('general.kegiatan'),
                        'active' => ['admin.kegiatan.index', 'admin.kegiatan.show', 'admin.kegiatan.edit'],
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
                        'type' => 3
                    ]
                ]
            ],
            [
                'name' => __('general.surat_pernyataan'),
                'icon' => '<i class="nav-icon fa fa-envelope"></i>',
                'title' => __('general.surat_pernyataan'),
                'active' => [
                    'admin.surat-pernyataan.',
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.surat_pernyataan'),
                        'title' => __('general.surat_pernyataan'),
                        'active' => ['admin.surat-pernyataan.index', 'admin.surat-pernyataan.show', 'admin.surat-pernyataan.edit'],
                        'route' => 'admin.surat-pernyataan.index',
                        'key' => 'surat-pernyataan',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.create_surat_pernyataan'),
                        'title' => __('general.create_surat_pernyataan'),
                        'active' => ['admin.surat-pernyataan.create'],
                        'route' => 'admin.surat-pernyataan.create',
                        'key' => 'surat-pernyataan',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.verification'),
                        'title' => __('general.verification'),
                        'active' => ['admin.surat-pernyataan.verification'],
                        'route' => 'admin.surat-pernyataan.verification',
                        'key' => 'surat-pernyataan',
                        'type' => 3
                    ]
                ]
            ],
            [
                'name' => __('general.dupak'),
                'icon' => '<i class="nav-icon fa fa-briefcase"></i>',
                'title' => __('general.dupak'),
                'active' => [
                    'admin.dupak.',
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.dupak'),
                        'title' => __('general.dupak'),
                        'active' => ['admin.dupak.index', 'admin.dupak.show', 'admin.dupak.edit'],
                        'route' => 'admin.dupak.index',
                        'key' => 'dupak',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.create_dupak'),
                        'title' => __('general.create_dupak'),
                        'active' => ['admin.dupak.create'],
                        'route' => 'admin.dupak.create',
                        'key' => 'dupak',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.verification'),
                        'title' => __('general.verification'),
                        'active' => ['admin.dupak.verification'],
                        'route' => 'admin.dupak.verification',
                        'key' => 'dupak',
                        'type' => 3
                    ]
                ]
            ],
            [
                'name' => __('general.bapak'),
                'icon' => '<i class="nav-icon fa fa-newspaper-o"></i>',
                'title' => __('general.bapak'),
                'active' => [
                    'admin.bapak.',
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.bapak'),
                        'title' => __('general.bapak'),
                        'active' => ['admin.bapak.index', 'admin.bapak.show', 'admin.bapak.edit'],
                        'route' => 'admin.bapak.index',
                        'key' => 'bapak',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.create_bapak'),
                        'title' => __('general.create_bapak'),
                        'active' => ['admin.bapak.create'],
                        'route' => 'admin.bapak.create',
                        'key' => 'bapak',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.verification'),
                        'title' => __('general.verification'),
                        'active' => ['admin.bapak.verification'],
                        'route' => 'admin.bapak.verification',
                        'key' => 'bapak',
                        'type' => 3
                    ]
                ]
            ],
            [
                'name' => __('general.staff'),
                'icon' => '<i class="nav-icon fa fa-users"></i>',
                'title' => __('general.staff'),
                'active' => [
                    'admin.staff.'
                ],
                'type' => 2,
                'data' => [
                    [
                        'name' => __('general.staff'),
                        'title' => __('general.staff'),
                        'active' => ['admin.staff.index'],
                        'route' => 'admin.staff.index',
                        'key' => 'staff',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.atasan'),
                        'title' => __('general.atasan'),
                        'active' => ['admin.staff.indexAtasan'],
                        'route' => 'admin.staff.indexAtasan',
                        'key' => 'staff',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.perancang'),
                        'title' => __('general.perancang'),
                        'active' => ['admin.staff.indexPerancang'],
                        'route' => 'admin.staff.indexPerancang',
                        'key' => 'staff',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.calon_perancang'),
                        'title' => __('general.calon_perancang'),
                        'active' => ['admin.staff.indexCalonAtasan'],
                        'route' => 'admin.staff.indexCalonAtasan',
                        'key' => 'staff',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.sekretariat'),
                        'title' => __('general.sekretariat'),
                        'active' => ['admin.staff.indexSekretariat'],
                        'route' => 'admin.staff.indexSekretariat',
                        'key' => 'staff',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.tim_penilai'),
                        'title' => __('general.tim_penilai'),
                        'active' => ['admin.staff.indexTimPenilai'],
                        'route' => 'admin.staff.indexTimPenilai',
                        'key' => 'staff',
                        'type' => 3
                    ],
                    [
                        'name' => __('general.create_staff'),
                        'title' => __('general.create_staff'),
                        'active' => ['admin.staff.create'],
                        'route' => 'admin.staff.create',
                        'key' => 'staff',
                        'type' => 3
                    ]
                ]
            ],
            [
                'name' => __('general.setting'),
                'icon' => '<i class="nav-icon fa fa-gear"></i>',
                'title' => __('general.setting'),
                'active' => [
                    'admin.permen.',
                    'admin.user-registered.',
                    'admin.gender.',
                    'admin.golongan.',
                    'admin.jabatan-perancang.',
                    'admin.jenjang-perancang.',
                    'admin.ms-kegiatan.',
                    'admin.pendidikan.',
                    'admin.unit-kerja.',
                    'admin.role.',

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
                        'name' => __('general.user_registered'),
                        'title' => __('general.user_registered'),
                        'active' => ['admin.user-registered.'],
                        'route' => 'admin.user-registered.index',
                        'key' => 'user-registered',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.gender'),
                        'title' => __('general.gender'),
                        'active' => ['admin.gender.'],
                        'route' => 'admin.gender.index',
                        'key' => 'gender',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.golongan'),
                        'title' => __('general.golongan'),
                        'active' => ['admin.golongan.'],
                        'route' => 'admin.golongan.index',
                        'key' => 'golongan',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.jabatan_perancang'),
                        'title' => __('general.jabatan_perancang'),
                        'active' => ['admin.jabatan-perancang.'],
                        'route' => 'admin.jabatan-perancang.index',
                        'key' => 'jabatan-perancang',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.jenjang_perancang'),
                        'title' => __('general.jenjang_perancang'),
                        'active' => ['admin.jenjang-perancang.'],
                        'route' => 'admin.jenjang-perancang.index',
                        'key' => 'jenjang-perancang',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.ms_kegiatan'),
                        'title' => __('general.ms_kegiatan'),
                        'active' => ['admin.ms-kegiatan.'],
                        'route' => 'admin.ms-kegiatan.index',
                        'key' => 'ms-kegiatan',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.pendidikan'),
                        'title' => __('general.pendidikan'),
                        'active' => ['admin.pendidikan.'],
                        'route' => 'admin.pendidikan.index',
                        'key' => 'pendidikan',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.unit_kerja'),
                        'title' => __('general.unit_kerja'),
                        'active' => ['admin.unit-kerja.'],
                        'route' => 'admin.unit-kerja.index',
                        'key' => 'unit-kerja',
                        'type' => 1
                    ],
                    [
                        'name' => __('general.role'),
                        'title' => __('general.role'),
                        'active' => ['admin.role.'],
                        'route' => 'admin.role.index',
                        'key' => 'role',
                        'type' => 1
                    ]
                ]
            ]
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
            'kegiatan',
            'surat-pernyataan',
            'dupak',
            'bapak',
            'staff',
            'gender',
            'user-registered',
            'golongan',
            'jabatan-perancang',
            'jenjang-perancang',
            'ms-kegiatan',
            'pendidikan',
            'unit-kerja',
            'role'
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
                'destroy' => [
                    'admin.'.$keyPermission.'.destroy'
                ]
            ];
        }


        return $listPermission;
    }
}
