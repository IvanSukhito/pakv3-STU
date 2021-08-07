<?php
if ( ! function_exists('render_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $jenjang_perancang_id
     * @param $listJenjangPerancang
     * @param bool $disabled
     * @param array $listDataKegiatan
     * @return string
     */
    function render_kegiatan_v3($ms_kegiatan, $jenjang_perancang_id, $listJenjangPerancang, $listDataKegiatan = []) {

        $getResult = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list->name;
        }

        $getDeep = $getResult['deep'];
        $getPath = $getResult['path'];
        $getKegiatan = $getResult['data'];
        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
                    <thead>
                    <tr>
                    <th width="80%" colspan="'.$getDeep.'">'.__('general.butir_kegiatan').'</th>
                    <th width="5%">AK</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Pelaksana</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

        if (isset($getPath[0])) {
            $html .= render_kegiatan_html_v3($getPath[0], $getPath, $getKegiatan, $jenjang_perancang_id, $listJenjangPerancang, $listJenjangPerancangData, $deep, $getDeep, $listDataKegiatan);
        }

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_kegiatan_html_v3')) {

    /**
     * @param $dataPath
     * @param $getPathMaster
     * @param $msKegiatan
     * @param $jejangPerancangId
     * @param $listJenjangPerancang
     * @param $listJenjangPerancangData
     * @param $deep
     * @param $totalDeep
     * @param array $listDataKegiatan
     * @param string $class
     * @return string
     * @throws Throwable
     */
    function render_kegiatan_html_v3($dataPath, $getPathMaster, $msKegiatan, $jejangPerancangId, $listJenjangPerancang, $listJenjangPerancangData, $deep, $totalDeep, $listDataKegiatan = [], $class = '') {
        $html = '';

        foreach ($dataPath as $list) {
            $getMsKegiatan = isset($msKegiatan[$list]) ? $msKegiatan[$list] : false;
            if (!$getMsKegiatan) {
                continue;
            }

            $name = $getMsKegiatan ? $getMsKegiatan->name : '&nbsp;';
            $newClass = $class.'kegiatan-'.$getMsKegiatan->id.' ';
            $ak = '&nbsp;';
            $satuan = '&nbsp;';
            $pelaksana = $getJenjangPerancang;

            if ($getMsKegiatan && $getMsKegiatan->ak > 0) {

                $tanggal = [];
                $judul = [];
                $deskripsi = [];
                $document = [];
                $document_physical = [];
                $kredit = 0;
                $getDataKegiatan = isset($listDataKegiatan[$getMsKegiatan->id]) ? $listDataKegiatan[$getMsKegiatan->id] : [];

                if($getDataKegiatan) {
                    foreach($getDataKegiatan as $list2) {
                        $get_dokument = json_decode($list2->dokument_pendukung, true);
                        $get_dokument_fisik = json_decode($list2->dokument_fisik, true);
                        $tanggal[] = $list2->tanggal;
                        $judul[] = $list2->judul;
                        $deskripsi[] = $list2->deskripsi;
                        $document[] = $get_dokument;
                        $document_physical[] = $get_dokument_fisik;
                        $kredit += $list2->kredit;
                    }
                }

                $data = [
                    'id' => $getMsKegiatan->id,
                    'name' => $getMsKegiatan->name,
                    'tanggal' => $tanggal,
                    'judul' => $judul,
                    'deskripsi' => $deskripsi,
                    'document' => $document,
                    'document_physical' => $document_physical,
                ];

                $name = view(env('ADMIN_TEMPLATE').'.page.kegiatan.form_kegiatan_view2', $data)->render();
                $ak = $kredit;

            }
            else {
                $ak = calculate_jenjang($jejangPerancangId, $getMsKegiatan->jenjang_perancang_id, $listJenjangPerancang, $getMsKegiatan->ak);
            }

            $satuan = $getMsKegiatan ? $getMsKegiatan->satuan : '&nbsp;';

            $html .= '<tr class="all-row '.$class.'" id="kegiatan-'.$getMsKegiatan->id.'" style="display: none;">';

            for($i=1; $i<=$deep; $i++) {
                $html .= '<td width="1%">&nbsp;</td>
                <td width="5%">'.$ak.'</td>
                <td width="10%">'.$satuan.'</td>
                <td width="15%">'.$pelaksana.'</td>';
            }

            $html .= '
                <td colspan="'.($totalDeep - $deep).'">'.$name.'</td>
                </tr>';

            if (isset($getPathMaster[$list])) {
                $html .= render_kegiatan_html_v3($getPathMaster[$list], $getPathMaster, $msKegiatan, $jejangPerancangId, $listJenjangPerancang, $listJenjangPerancangData, $deep+1, $totalDeep, $listDataKegiatan, $newClass);
            }
        }

        return $html;
    }
}

if ( ! function_exists('render_kegiatan')) {
    /**
     * @param $ms_kegiatan
     * @param $jenjang_perancang_id
     * @param $listJenjangPerancang
     * @param bool $disabled
     * @param array $listDataKegiatan
     * @return string
     */
    function render_kegiatan($ms_kegiatan, $jenjang_perancang_id, $listJenjangPerancang, $disabled = false, $listDataKegiatan = []) {

        $getResult = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list->name;
        }

        $getDeep = $getResult['deep'];
        $getPath = $getResult['path'];
        $getKegiatan = $getResult['data'];
        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
                    <thead>
                    <tr>
                    <th width="70%" colspan="'.$getDeep.'">Butir Kegiatan</th>
                    <th width="5%">AK</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Pelaksana</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';


        if (isset($getPath[0])) {
            $html .= render_kegiatan_html($getPath[0], $getPath, $getKegiatan, $jenjang_perancang_id, $listJenjangPerancang, $listJenjangPerancangData, $deep, $getDeep, $disabled, '', $listDataKegiatan);
        }

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_kegiatan_html')) {

    /**
     * @param $dataPath
     * @param $getPathMaster
     * @param $msKegiatan
     * @param $jejangPerancangId
     * @param $listJenjangPerancang
     * @param $listJenjangPerancangData
     * @param $deep
     * @param $totalDeep
     * @param $disabled
     * @param string $class
     * @param array $listDataKegiatan
     * @return string
     * @throws Throwable
     */
    function render_kegiatan_html($dataPath, $getPathMaster, $msKegiatan, $jejangPerancangId, $listJenjangPerancang, $listJenjangPerancangData, $deep, $totalDeep, $disabled, $class = '', $listDataKegiatan = []) {
        $html = '';

        foreach ($dataPath as $list) {
            $getMsKegiatan = isset($msKegiatan[$list]) ? $msKegiatan[$list] : false;
            if (!$getMsKegiatan) {
                continue;
            }

            $getJenjangPerancang = isset($listJenjangPerancangData[$getMsKegiatan->jenjang_perancang_id]) ? $listJenjangPerancangData[$getMsKegiatan->jenjang_perancang_id] : '&nbsp;';

            $name = $getMsKegiatan ? $getMsKegiatan->name : '&nbsp;';
            $ak = '&nbsp;';
            $satuan = '&nbsp;';
            $pelaksana = $getJenjangPerancang;

            if ($deep == 0) {
                $class = ' kegiatan-'.$getMsKegiatan->id.' ';
            }

            if ($getMsKegiatan && $getMsKegiatan->ak > 0) {

                $data = [
                    'id' => $getMsKegiatan->id,
                    'name' => $getMsKegiatan->name,
                    'disabled' => $disabled
                ];

                if ($disabled == true) {

                    $tanggal = [];
                    $judul = [];
                    $deskripsi = [];
                    $document = [];
                    $document_physical = [];
                    $kredit = 0;
                    $getDataKegiatan = isset($listDataKegiatan[$getMsKegiatan->id]) ? $listDataKegiatan[$getMsKegiatan->id] : [];
                    if($getDataKegiatan) {
                        foreach($getDataKegiatan as $list2) {
                            $get_dokument = json_decode($list2->dokument, true);
                            $get_dokument_fisik = json_decode($list2->dokumen_fisik, true);
                            $tanggal[] = $list2->tanggal;
                            $judul[] = $list2->judul;
                            $deskripsi[] = $list2->deskripsi;
                            $document[] = $get_dokument;
                            $document_physical[] = $get_dokument_fisik;
                            $kredit += $list2->kredit;
                        }
                    }

                    $data = [
                        'id' => $getMsKegiatan->id,
                        'name' => $getMsKegiatan->name,
                        'tanggal' => $tanggal,
                        'judul' => $judul,
                        'deskripsi' => $deskripsi,
                        'document' => $document,
                        'document_physical' => $document_physical,
                    ];

                    $name = view(env('ADMIN_TEMPLATE').'.page.kegiatan.form_kegiatan_view', $data)->render();
                    $ak = $kredit;
                }
                else {
                    $name = view(env('ADMIN_TEMPLATE').'.page.kegiatan.form_kegiatan', $data)->render();
                    $ak = calculate_jenjang($jejangPerancangId, $getMsKegiatan->jenjang_perancang_id, $listJenjangPerancang, $getMsKegiatan->ak);
                }
                $satuan = $getMsKegiatan ? $getMsKegiatan->satuan : '&nbsp;';

            }

            $html .= '<tr class="all-row permen-'.$getMsKegiatan->permen_id.' '.$class.'">';

            for($i=1; $i<=$deep; $i++) {
                $html .= '<td width="1%">&nbsp;</td>';
            }

            $html .= '
                <td width="'. (70 - ($deep)) .'%" colspan="'.($totalDeep - $deep).'">'.$name.'</td>
                <td width="5%">'.$ak.'</td>
                <td width="10%">'.$satuan.'</td>
                <td width="15%">'.$pelaksana.'</td>
                </tr>';

            if (isset($getPathMaster[$list])) {
                $html .= render_kegiatan_html($getPathMaster[$list], $getPathMaster, $msKegiatan, $jejangPerancangId, $listJenjangPerancang, $listJenjangPerancangData, $deep+1, $totalDeep, $disabled, $class, $listDataKegiatan);
            }
        }

        return $html;
    }
}

if ( ! function_exists('render_kegiatan_old')) {
    function render_kegiatan_old($ms_kegiatan, $jenjang_perancang_id, $disabled = false) {
        $html = '<table class="table table-kegiatan table-bordered table-striped">
                    <thead>
                    <tr>
                    <th width="70%">Butir Kegiatan</th>
                    <th width="5%">AK</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Pelaksana</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

        $get_jenjang_perancang = \App\Codes\Models\JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();
        $list_jenjang_perancang = [];
        foreach($get_jenjang_perancang as $list) {
            $list_jenjang_perancang[$list->order_high] = $list->id;
        }

        $html .= render_tree($ms_kegiatan, $jenjang_perancang_id, $list_jenjang_perancang, 0, 0, null, $disabled);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_tree')) {
    function render_tree($elements, $jenjang_perancang_id, $list_jenjang_perancang, $parentId = 0, $total_loop = 0, $parent_name = null, $disabled = false)
    {
        $branch = '';
        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $temp_parent_name = 'all_row ';

                if($parentId == 0) {
                    $parent_name = $temp_parent_name.preg_replace("/[^A-Za-z0-9?!]/",'','_'.strtolower($element->name));
                }

                $children = render_tree($elements, $jenjang_perancang_id, $list_jenjang_perancang, $element->id, $total_loop + 1, $parent_name, $disabled);

                $print_name = $element->name;
                $print_ak = '';
                $print_satuan = '';
                $print_jenjang_perancang = '';
                if($total_loop == 0) {
                    $total_padding_left = 8;
                }
                else {
                    $total_padding_left = 30 * $total_loop;
                }

                if($element->ak != 0) {
                    $data = [
                        'id' => $element->id,
                        'name' => $element->name,
                        'disabled' => $disabled
                    ];

                    $get_jenjang = $element->getJenjangPerancang()->first();

                    $print_name = view(env('ADMIN_TEMPLATE').'.page.kegiatan.form_kegiatan', $data)->render();
                    try {
                        $print_ak = calculate_jenjang($jenjang_perancang_id, $get_jenjang->id, $list_jenjang_perancang, $element->ak);
                        $print_satuan = $element->satuan;
                        $print_jenjang_perancang = $get_jenjang->name;
                    }
                    catch (Exception $e) {
//                        dd($e->getMessage(), $get_jenjang, $element);
                        $print_ak = '';
                        $print_satuan = $element->satuan;
                        $print_jenjang_perancang = 'INI Tidak di temukan';
                    }
                }
                $branch .= '<tr class="'.$parent_name.'" id="'.$element->id.'">
                        <td style="padding-left: ' . $total_padding_left . 'px">'.$print_name.'</td>
                        <td>' . $print_ak . '</td>
                        <td>' . $print_satuan. '</td>
                        <td>' . $print_jenjang_perancang. '</td>
                        </tr>';
                if (strlen($children) > 0) {
                    $branch .= $children;
                }
            }
        }

        return $branch;
    }
}

if ( ! function_exists('render_kegiatan_view')) {
    function render_kegiatan_view($ms_kegiatan, $jenjang_perancang_id, $disabled = false) {
        $html = '<table class="table table-kegiatan table-bordered table-striped">
                    <thead>
                    <tr>
                    <th width="70%">Butir Kegiatan</th>
                    <th width="5%">AK</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Pelaksana</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

        $get_jenjang_perancang = \App\Codes\Models\JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();
        $list_jenjang_perancang = [];
        foreach($get_jenjang_perancang as $list) {
            $list_jenjang_perancang[$list->order_high] = $list->id;
        }

        $html .= render_tree_view($ms_kegiatan, $jenjang_perancang_id, $list_jenjang_perancang, 0, 0, null);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_tree_view')) {
    function render_tree_view($elements, $list_kegiatan_total, $jenjang_perancang_id, $list_jenjang_perancang, $parentId = 0, $total_loop = 0, $parent_name = null)
    {
        $branch = '';
        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $temp_parent_name = 'all_row ';

                if($parentId == 0) {
                    $parent_name = $temp_parent_name.preg_replace("/[^A-Za-z0-9?!]/",'','_'.strtolower($element->name));
                }

                $children = render_tree_view($elements, $list_kegiatan_total, $jenjang_perancang_id, $list_jenjang_perancang, $element->id, $total_loop + 1, $parent_name);

                $print_name = $element->name;
                $print_ak = '';
                $print_satuan = '';
                $print_jenjang_perancang = '';
                if($total_loop == 0) {
                    $total_padding_left = 8;
                }
                else {
                    $total_padding_left = 30 * $total_loop;
                }

                if($element->ak != 0) {

                    $get_jenjang = $element->getJenjangPerancang()->first();

                    $print_name = $element->name;
                    $total_kegiatan = isset($list_kegiatan_total[$element->id]) ? $list_kegiatan_total[$element->id] : [];
                    $print_ak = calculate_jenjang($jenjang_perancang_id, $get_jenjang->id, $list_jenjang_perancang, $element->ak);
                    if(count($total_kegiatan) > 0) {
                        $print_ak .= ' * '.count($total_kegiatan);
                    }
                    $print_satuan = $element->satuan;
                    $print_jenjang_perancang = $get_jenjang->name;
                }
                $branch .= '<tr class="'.$parent_name.'" id="'.$element->id.'">
                        <td style="padding-left: ' . $total_padding_left . 'px">'.$print_name.'</td>
                        <td>' . $print_ak . '</td>
                        <td>' . $print_satuan . '</td>
                        <td>' . $print_jenjang_perancang . '</td>
                        </tr>';
                if (strlen($children) > 0) {
                    $branch .= $children;
                }
            }
        }

        return $branch;
    }
}

if ( ! function_exists('render_kegiatan_view_detail')) {
    function render_kegiatan_view_detail($kegiatan, $list_kegiatan, $listDataKegiatan) {
        $html = '<table class="table table-kegiatan table-striped table-hover">
                    <thead>
                    <tr>
                    <th width="70%">Butir Kegiatan</th>
                    <th width="5%">AK</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Pelaksana</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

        $get_jenjang_perancang = \App\Codes\Models\JenjangPerancang::where('status', 1)->orderBy('order_high', 'ASC')->get();
        $list_jenjang_perancang = [];
        foreach($get_jenjang_perancang as $list) {
            $list_jenjang_perancang[$list->order_high] = $list->id;
        }

        $html .= render_tree_view_detail($kegiatan, $list_kegiatan, $list_jenjang_perancang,0, 0, null, $listDataKegiatan);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_tree_view_detail')) {
    function render_tree_view_detail($elements, $list_kegiatan, $list_jenjang_perancang, $parentId = 0, $total_loop = 0, $parent_name = null)
    {
        $branch = '';
        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $temp_parent_name = 'all_row ';

                if($parentId == 0) {
                    $parent_name = $temp_parent_name.preg_replace("/[^A-Za-z0-9?!]/",'','_'.strtolower($element->name));
                }

                $children = render_tree_view_detail($elements, $list_kegiatan, $list_jenjang_perancang, $element->id, $total_loop + 1, $parent_name);

                $print_name = $element->name;
                $print_ak = '';
                $print_satuan = '';
                $print_jenjang_perancang = '';
                if($total_loop == 0) {
                    $total_padding_left = 8;
                }
                else {
                    $total_padding_left = 30 * $total_loop;
                }

                if($element->ak != 0) {

                    $tanggal = [];
                    $judul = [];
                    $deskripsi = [];
                    $document = [];
                    $document_physical = [];
                    $kegiatan = isset($list_kegiatan[$element->id]) ? $list_kegiatan[$element->id] : null;
                    $kredit = 0;
                    if($kegiatan) {
                        foreach($kegiatan as $list) {
                            $get_dokument = json_decode($list->dokument, true);
                            $get_dokument_fisik = json_decode($list->dokumen_fisik, true);
                            $tanggal[] = $list->tanggal;
                            $judul[] = $list->judul;
                            $deskripsi[] = $list->deskripsi;
                            $document[] = $get_dokument;
                            $document_physical[] = $get_dokument_fisik;
                            $kredit += $list->kredit;
                        }
                    }

                    $data = [
                        'id' => $element->id,
                        'name' => $element->name,
                        'tanggal' => $tanggal,
                        'judul' => $judul,
                        'deskripsi' => $deskripsi,
                        'document' => $document,
                        'document_physical' => $document_physical,
                    ];

                    $get_jenjang = $element->getJenjangPerancang()->first();
                    $print_name = view(env('ADMIN_TEMPLATE').'.page.kegiatan.form_kegiatan_view', $data)->render();
                    $print_ak = $kredit > 0 ? $kredit : '';
                    $print_satuan = $element->satuan;
                    $print_jenjang_perancang = $get_jenjang->name;
                }
                $branch .= '<tr class="'.$parent_name.'" id="'.$element->id.'">
                        <td style="padding-left: ' . $total_padding_left . 'px">'.$print_name.'</td>
                        <td>' . $print_ak . '</td>
                        <td>' . $print_satuan. '</td>
                        <td>' . $print_jenjang_perancang. '</td>
                        </tr>';
                if (strlen($children) > 0) {
                    $branch .= $children;
                }
            }
        }

        return $branch;
    }
}

if ( ! function_exists('render_kegiatan_dupak_pdf_detail')) {
    function render_kegiatan_dupak_pdf_detail($kegiatan, $list_kegiatan, $list_old_kegiatan, $sum_total_lama, $sum_total_baru, $sum_total) {

        $total_kredit_lama = $sum_total_lama;
        $total_kredit = $sum_total_baru;
        $total_kredit_baru = $sum_total;

        $html = '<table class="table border" cellpadding="0" cellspacing="0" style="padding-top: 3px;"><tr>
                    <td class="center" width="5%">No</td>
                    <td class="center" width="47%">UNSUR DAN SUB UNSUR YANG DINILAI</td>
                    <td class="center" colspan="3" width="24%">INSTANSI PENGUSUL</td>
                    <td class="center" colspan="3" width="24%">INSTANSI PENILAI</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td class="center" width="8%">LAMA</td>
                    <td class="center" width="8%">BARU</td>
                    <td class="center" width="8%">JUMLAH</td>
                    <td class="center" width="8%">LAMA</td>
                    <td class="center" width="8%">BARU</td>
                    <td class="center" width="8%">JUMLAH</td>
                </tr>
                <tr>
                    <td class="center" width="5%">1</td>
                    <td class="center" width="47%">2</td>
                    <td class="center" width="8%">3</td>
                    <td class="center" width="8%">4</td>
                    <td class="center" width="8%">5</td>
                    <td class="center" width="8%">6</td>
                    <td class="center" width="8%">7</td>
                    <td class="center" width="8%">8</td>
                </tr>';

        $html .= render_tree_kegiatan_dupak_pdf_detail($kegiatan, $list_kegiatan, $list_old_kegiatan,0, 0);

        $html .= '<tr>
                    <td colspan="2" class="right">JUMLAH KESELURUHAN</td>
                    <td class="center">'.$total_kredit_lama.'</td>
                    <td class="center">'.$total_kredit.'</td>
                    <td class="center">'.$total_kredit_baru.'</td>
                    <td class="center"></td>
                    <td class="center">&nbsp;</td>
                    <td class="center">&nbsp;</td>
                </tr></table>';

        return $html;
    }
}

if ( ! function_exists('render_tree_kegiatan_dupak_pdf_detail')) {
    function render_tree_kegiatan_dupak_pdf_detail($elements, $list_kegiatan, $list_old_kegiatan, $parentId = 0, $total_loop = 0)
    {
        $branch = '';

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {

                $children = render_tree_kegiatan_dupak_pdf_detail($elements, $list_kegiatan, $list_old_kegiatan, $element->id, $total_loop + 1);

                $print_name = $element->name;
                $get_kegiatan = isset($list_kegiatan[$element->id]) ? $list_kegiatan[$element->id] : [];
                $print_k_lama = isset($list_old_kegiatan[$element->id]) ? $list_old_kegiatan[$element->id] : 0;
                $print_k_baru = 0;

                if($get_kegiatan) {
                    foreach($get_kegiatan as $list) {
                        $print_k_baru += $list->kredit;
                    }
                }

                $print_k_total = $print_k_lama + $print_k_baru;

                if($total_loop == 0) {
                    $total_padding_left = 8;
                }
                else {
                    $total_padding_left = 30 * $total_loop;
                }

                if($print_k_lama <= 0) {
                    $print_k_lama = '';
                }
                if($print_k_baru <= 0) {
                    $print_k_baru = '';
                }
                if($print_k_total <= 0) {
                    $print_k_total = '';
                }

                $branch .= '<tr>
                    <td class="center">&nbsp;</td>
                    <td class="left" style="padding-left: ' . $total_padding_left . 'px">'.$print_name.'</td>
                    <td class="center">' . $print_k_lama . '</td>
                    <td class="center">' . $print_k_baru . '</td>
                    <td class="center">' . $print_k_total . '</td>
                    <td class="center"></td>
                    <td class="center">&nbsp;</td>
                    <td class="center">&nbsp;</td>
                    </tr>';
                if (strlen($children) > 0) {
                    $branch .= $children;
                }
            }
        }

        return $branch;
    }

    function is_previous_route_name(string $routeName) : bool
    {
        $previousRequest = app('request')->create(URL::previous());

        try {
            $previousRouteName = app('router')->getRoutes()->match($previousRequest)->getName();
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            // Exception is thrown if no mathing route found.
            // This will happen for example when comming from outside of this app.
            return false;
        }

        return $previousRouteName === $routeName;
    }
}
