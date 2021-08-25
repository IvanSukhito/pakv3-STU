<?php

if ( ! function_exists('create_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $jenjangPerancangId
     * @return string
     * @throws Throwable
     */
    function create_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $jenjangPerancangId) {

        $getDeep = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list;
        }

        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
                    <thead>
                    <tr>
                    <th width="60%" colspan="'.$getDeep.'">'.__('general.butir_kegiatan').'</th>
                    <th width="15%" colspan="3" class="text-center">AK</th>
                    <th width="10%" class="text-center">Satuan</th>
                    <th width="15%" class="text-center">Pelaksana</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

        $html .= render_create_kegiatan_v3($ms_kegiatan, $listJenjangPerancangData, $deep, $getDeep, $jenjangPerancangId);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_create_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $deep
     * @param $getDeep
     * @param $jenjangPerancangId
     * @param string $parentClass
     * @return string
     */
    function render_create_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $deep, $getDeep, $jenjangPerancangId, $parentClass = '') {
        $html = '';

        foreach ($ms_kegiatan as $list) {

            $getId = $list['id'];
            $getName = $list['name'];
            $getAk = $list['ak'] > 0 ? $list['ak'] : '';
            $getJenjangKegiatan = $list['jenjang_perancang_id'];
            $getSatuan = strlen($list['satuan']) > 0 ? $list['satuan'] : '';
            $getPelaksana = isset($listJenjangPerancang[$list['jenjang_perancang_id']]) ? $listJenjangPerancang[$list['jenjang_perancang_id']]->name : '';
            $getChild = $list['have_child'] == 1 ? $list['child'] : [];
            $addClass = $parentClass.' kegiatan-'.$getId;

            $addHtmlAk = '<td width="15%" colspan="3">&nbsp;</td>';
            if ($getAk > 0) {

                $getName = '<a href="#" class="click-kegiatan" data-id="'.$getId.'">'.$getName.'</a>';

                $getNewAk = calculate_jenjang($jenjangPerancangId, $getJenjangKegiatan, $listJenjangPerancang, $getAk);
                if ($getAk != $getNewAk) {
                    $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                        '<td width="5%" class="text-center">80%</td>'.
                        '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                }
                else {
                    $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                        '<td width="5%" class="text-center">100%</td>'.
                        '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                }
            }

            $addHtmlTd = '';
            if ($deep > 0) {
                $addHtmlTd = str_repeat('<td width="1%">&nbsp;</td>', $deep);
            }
            $newDeep = $getDeep - $deep;

            $html .= '<tr class="all-row'.$addClass.'">'.$addHtmlTd.'
                    <td colspan="'.$newDeep.'">'.$getName.'</td>
                    '.$addHtmlAk.'
                    <td width="10%" class="text-center">'.$getSatuan.'</td>
                    <td width="15%" class="text-center">'.$getPelaksana.'</td>
                    </tr>';

            if ($getChild) {
                $html .= render_create_kegiatan_v3($getChild, $listJenjangPerancang, $deep + 1, $getDeep, $jenjangPerancangId, $addClass);
            }

        }

        return $html;

    }
}

if ( ! function_exists('view_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $jenjangPerancangId
     * @return string
     * @throws Throwable
     */
    function view_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $jenjangPerancangId) {

        $getDeep = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list;
        }

        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
                    <thead>
                    <tr>
                    <th width="45%" colspan="'.$getDeep.'">'.__('general.butir_kegiatan').'</th>
                    <th width="10%" class="text-center">'.__('general.date').'</th>
                    <th width="15%" colspan="3" class="text-center">AK</th>
                    <th width="10%" class="text-center">Satuan</th>
                    <th width="15%" class="text-center">'.__('general.evidence').'</th>
                    <th width="5%" class="text-center">'.__('general.status').'</th>
                    <th width="5%" class="text-center">'.__('general.created').'</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

        $html .= render_view_kegiatan_v3($ms_kegiatan, $listJenjangPerancangData, $deep, $getDeep, $jenjangPerancangId);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_view_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $deep
     * @param $getDeep
     * @param $jenjangPerancangId
     * @param string $parentClass
     * @param string $prevName
     * @return string
     */
    function render_view_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $deep, $getDeep, $jenjangPerancangId, $parentClass = '', $prevName = '', $step = 0) {
        $html = '';

        foreach ($ms_kegiatan as $list) {

            $getId = $list['id'];
            $getName = $list['name'];
            $getAk = $list['ak'] > 0 ? $list['ak'] : '';
            $getSatuan = strlen($list['satuan']) > 0 ? $list['satuan'] : '';
            $getTanggal = '';
            $getBukti = '';
            $getStatus = '';
            $getCreated = '';
            $getChild = $list['have_child'] == 1 ? $list['child'] : [];
            $addClass = $parentClass.' kegiatan-'.$getId;
            $addLabel = '';

            $totalColspan = 0;
            $addHtmlAk = '';
            $htmlColspan = '';
            $listIds = [];
            if ($getAk > 0) {

                if ($step > 1 && strlen($getName) <= 100 && strlen($prevName) > 0) {
                    $getName = $prevName.': '.$getName;
                }

                $getName = '<a href="#" class="click-kegiatan" data-id="'.$getId.'">'.$getName.'</a>';

                $getDataInput = $list['data'] ?? false;
                if ($getDataInput) {
                    $totalColspan = count($getDataInput);
                    foreach ($getDataInput as $listInput) {

                        $getListStatus = get_list_kegiatan();
                        $getKegiatanAk = $listInput['kredit'];
                        $getDokument = json_decode($listInput['dokument_pendukung'], true);
                        $getDokumentFisik = json_decode($listInput['dokument_fisik'], true);
                        $getStatus = $getListStatus[$listInput['status']] ?? '-';
                        $getCreated = date('d-M-y', strtotime($listInput['created_at']));
                        $getListDokument = [];
                        $getListDokumentFisik = [];
                        if ($getDokument) {
                            foreach ($getDokument as $listDokument) {
                                $getListDokument[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                                $getBukti .= strlen($getBukti) > 1 ? ', <a href="'.asset($listDokument['location']).'">'.$listDokument['name'].'</a>' : '<a href="'.asset($listDokument['location']).'">'.$listDokument['name'].'</a>';
                            }
                        }
                        if ($getDokumentFisik) {
                            foreach ($getDokumentFisik as $listDokument) {
                                $getListDokumentFisik[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                                $getBukti .= strlen($getBukti) > 1 ? ', <a href="'.asset($listDokument['location']).'">'.$listDokument['name'].'</a>' : '<a href="'.asset($listDokument['location']).'">'.$listDokument['name'].'</a>';
                            }
                        }

                        $listIds[] = [
                            'id' => $listInput['id'],
                            'tanggal' => date('d-M-Y', strtotime($listInput['tanggal'])),
                            'kredit_ak' => $getAk,
                            'kredit' => $getKegiatanAk,
                            'dokument' => $getListDokument,
                            'dokument_fisik' => $getListDokumentFisik,
                            'created' => $getCreated
                        ];

                    }

                    if (count($listIds) > 1) {
                        $htmlColspan .= ' rowspan="'.count($listIds).'" ';
                    }

                }

                if (count($listIds) > 0) {
                    $addLabel = '<input type="hidden" id="kegiatan_hidden_'.$getId.'" value=\''.json_encode($listIds).'\'/>';
                }

            }
            else {
                $addHtmlAk = '<td width="15%" colspan="3">&nbsp;</td>';
            }

            $addHtmlTd = '';
            if ($deep > 0) {
                $addHtmlTd = str_repeat('<td'.$htmlColspan.' width="1%">&nbsp;</td>', $deep);
            }
            $newDeep = $getDeep - $deep;

            if ($deep == 0 || $getAk > 0) {

                if ($totalColspan <= 0) {

                    $html .= '<tr class="all-row'.$addClass.'">'.$addHtmlTd.'
                        <td colspan="'.$newDeep.'">'.$addLabel.$getName.'</td>
                        <td width="10%" class="text-center">'.$getTanggal.'</td>
                        '.$addHtmlAk.'
                        <td width="10%" class="text-center">'.$getSatuan.'</td>
                        <td width="15%" class="text-center">'.$getBukti.'</td>
                        <td width="5%" class="text-center">'.$getStatus.'</td>
                        <td width="1%" class="text-center">'.$getCreated.'</td>
                        </tr>';

                }
                else if ($totalColspan == 1) {

                    foreach ($listIds as $listDataKegiatan) {

                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';
                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $getCreated = $listDataKegiatan['created'];

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        $html .= '<tr class="all-row'.$addClass.'">'.$addHtmlTd.'
                        <td colspan="'.$newDeep.'">'.$addLabel.$getName.'</td>
                        <td width="10%" class="text-center">'.$listDataKegiatan['tanggal'].'</td>
                        '.$addHtmlAk.'
                        <td width="10%" class="text-center">'.$getSatuan.'</td>
                        <td width="15%">'.$getBukti.'</td>
                        <td width="5%" class="text-center">'.$getStatus.'</td>
                        <td width="1%" class="text-center">'.$getCreated.'</td>
                        </tr>';

                    }

                }
                else {

                    foreach ($listIds as $indexing => $listDataKegiatan) {

                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';
                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getCreated = $listDataKegiatan['created'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        if ($indexing == 0) {

                            $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                        <td' . $htmlColspan . ' colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                        <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                        ' . $addHtmlAk . '
                        <td' . $htmlColspan . ' width="10%" class="text-center">' . $getSatuan . '</td>
                        <td width="15%" class="text-center">' . $getBukti . '</td>
                        <td' . $htmlColspan . ' width="5%" class="text-center">' . $getStatus . '</td>
                        <td width="%" class="text-center">' . $getCreated . '</td>
                        </tr>';

                        } else {

                            $html .= '<tr class="all-row' . $addClass . '">
                            <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                            ' . $addHtmlAk . '
                            <td width="15%" class="text-center">' . $getBukti . '</td>
                            <td width="1%" class="text-center">' . $getCreated . '</td>
                            </tr>';

                        }
                    }

                }

            }
            else {
                $deep -= 1;
            }

            $getOldName = $getName;

            if ($getChild) {
                $html .= render_view_kegiatan_v3($getChild, $listJenjangPerancang, $deep + 1, $getDeep, $jenjangPerancangId, $addClass, $getOldName, $step + 1);
            }

        }

        return $html;

    }
}

if ( ! function_exists('persetujuan_sp_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $jenjangPerancangId
     * @param int $readonly
     * @return string
     */
    function persetujuan_sp_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $jenjangPerancangId, $readonly = 0) {

        $getDeep = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list;
        }

        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
            <thead>
            <tr>
            <th width="45%" colspan="'.$getDeep.'">'.__('general.butir_kegiatan').'</th>
            <th width="10%" class="text-center">'.__('general.date').'</th>
            <th width="15%" colspan="3" class="text-center">AK</th>
            <th width="10%" class="text-center">Satuan</th>
            <th width="15%" class="text-center">'.__('general.evidence').'</th>
            <th width="5%" class="text-center">'.__('general.action').'</th>
            </tr>
            </thead>
            <tbody>
            ';


        $html .= render_persetujuan_sp_kegiatan_v3($ms_kegiatan, $listJenjangPerancangData, $deep, $getDeep, $jenjangPerancangId, $readonly);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_persetujuan_sp_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $deep
     * @param $getDeep
     * @param $jenjangPerancangId
     * @param $readonly
     * @param string $parentClass
     * @param string $prevName
     * @return string
     */
    function render_persetujuan_sp_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $deep, $getDeep, $jenjangPerancangId, $readonly, $parentClass = '', $prevName = '', $step = 0) {
        $html = '';

        foreach ($ms_kegiatan as $list) {

            $getId = $list['id'];
            $getName = $list['name'];
            $getAk = $list['ak'] > 0 ? $list['ak'] : '';
            $getSatuan = strlen($list['satuan']) > 0 ? $list['satuan'] : '';
            $getAction = '';
            $getChild = $list['have_child'] == 1 ? $list['child'] : [];
            $addClass = $parentClass.' kegiatan-'.$getId;
            $addLabel = '';

            $totalColspan = 0;
            $htmlColspan = '';
            $listIds = [];
            if ($getAk > 0) {

                if ($step > 1 && strlen($getName) <= 100 && strlen($prevName) > 0) {
                    $getName = $prevName.': <br/> &nbsp;'.$getName;
                }

                $getDataInput = $list['data'] ?? false;
                if ($getDataInput) {

                    $getName = '<label>'.$getName.'</label>';

                    $totalColspan = count($getDataInput);
                    foreach ($getDataInput as $listInput) {

                        $getKegId = $listInput['sp_kegiatan_id'];
                        $getKegiatanAk = $listInput['kredit'];
                        $getDokument = json_decode($listInput['dokument_pendukung'], true);
                        $getDokumentFisik = json_decode($listInput['dokument_fisik'], true);

                        $getListDokument = [];
                        $getListDokumentFisik = [];
                        if ($getDokument) {
                            foreach ($getDokument as $listDokument) {
                                $getListDokument[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                            }
                        }
                        if ($getDokumentFisik) {
                            foreach ($getDokumentFisik as $listDokument) {
                                $getListDokumentFisik[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                            }
                        }

                        $listIds[] = [
                            'id' => $listInput['id'],
                            'tanggal' => date('d-M-Y', strtotime($listInput['tanggal'])),
                            'kredit_ak' => $getAk,
                            'kredit' => $getKegiatanAk,
                            'dokument' => $getListDokument,
                            'dokument_fisik' => $getListDokumentFisik,
                            'sp_kegiatan_id' => $getKegId,
                            'sp_kegiatan_message' => $listInput['sp_kegiatan_message'],
                            'sp_kegiatan_status' => $listInput['sp_kegiatan_status']
                        ];

                    }

                    if (count($listIds) > 1) {
                        $htmlColspan .= ' rowspan="'.count($listIds).'" ';
                    }

                }

                if (count($listIds) > 0) {
                    $addLabel = '<input type="hidden" id="kegiatan_hidden_'.$getId.'" value=\''.json_encode($listIds).'\'/>';
                }

            }

            $addHtmlTd = '';
            if ($deep > 0) {
                $addHtmlTd = str_repeat('<td'.$htmlColspan.' width="1%">&nbsp;</td>', $deep);
            }
            $newDeep = $getDeep - $deep;

            if ($deep == 0 || $getAk > 0) {

                $getListStatus = get_list_status_surat_pernyataan();

                if ($totalColspan <= 0) {

                    $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                        <td colspan="' . ($newDeep + 6) . '">' . $addLabel . $getName . '</td>
                        <td width="5%" class="text-center">' . $getAction . '</td>
                        </tr>';

                }
                else if ($totalColspan == 1) {

                    foreach ($listIds as $listDataKegiatan) {

                        $getKegId = $listDataKegiatan['sp_kegiatan_id'];
                        $getKegStatus = $listDataKegiatan['sp_kegiatan_status'];
                        $getKegMessage = $listDataKegiatan['sp_kegiatan_message'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';

                        if ($readonly == 1) {
                            $getAction = $getListStatus[$getKegStatus] ?? '-';
                        }
                        else {
                            $setStatusOk = $getKegStatus == 80 ? ' checked' : '';
                            $setStatusCancel = $getKegStatus == 99 ? ' checked' : '';
                            $getAction = '<label><input type="radio" class="radio_button_ok" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_80" name="action_kegiatan['.$getKegId.']" value="80"'.$setStatusOk.'/> Disetujui</label>
                                <label><input type="radio" class="radio_button_cancel" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_99" name="action_kegiatan['.$getKegId.']" value="99"'.$setStatusCancel.'/> Ditolak</label>
                                <input type="hidden" class="message_kegiatan" id="message_kegiatan_'.$getKegId.'" name="message_kegiatan['.$getKegId.']" value="'.$getKegMessage.'">';
                        }

                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                            <td colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                            <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                            ' . $addHtmlAk . '
                            <td width="10%" class="text-center">' . $getSatuan . '</td>
                            <td width="15%">' . $getBukti . '</td>
                            <td width="5%" class="text-center">' . $getAction . '</td>
                            </tr>';

                    }

                }
                else {

                    foreach ($listIds as $indexing => $listDataKegiatan) {

                        $getKegId = $listDataKegiatan['sp_kegiatan_id'];
                        $getKegStatus = $listDataKegiatan['sp_kegiatan_status'];
                        $getKegMessage = $listDataKegiatan['sp_kegiatan_message'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';
                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        if ($readonly == 1) {
                            $getAction = $getListStatus[$getKegStatus] ?? '-';
                        }
                        else {
                            $setStatusOk = $getKegStatus == 80 ? ' checked' : '';
                            $setStatusCancel = $getKegStatus == 99 ? ' checked' : '';
                            $getAction = '<label><input type="radio" class="radio_button_ok" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_80" name="action_kegiatan['.$getKegId.']" value="80"'.$setStatusOk.'/> Disetujui</label>
                                <label><input type="radio" class="radio_button_cancel" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_99" name="action_kegiatan['.$getKegId.']" value="99"'.$setStatusCancel.'> Ditolak</label>
                                <input type="hidden" class="message_kegiatan" id="message_kegiatan_'.$getKegId.'" name="message_kegiatan['.$getKegId.']" value="'.$getKegMessage.'">';
                        }

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        if ($indexing == 0) {

                            $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                                <td' . $htmlColspan . ' colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                                <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                                ' . $addHtmlAk . '
                                <td' . $htmlColspan . ' width="10%" class="text-center">' . $getSatuan . '</td>
                                <td width="15%" class="text-center">' . $getBukti . '</td>
                                <td width="5%" class="text-center">' . $getAction . '</td>
                                </tr>';

                        } else {

                            $html .= '<tr class="all-row' . $addClass . '">
                                <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                                ' . $addHtmlAk . '
                                <td width="15%" class="text-center">' . $getBukti . '</td>
                                <td width="5%" class="text-center">' . $getAction . '</td>
                                </tr>';

                        }
                    }

                }

            }
            else {
                $deep -= 1;
            }

            $getOldName = $getName;

            if ($getChild) {
                $html .= render_persetujuan_sp_kegiatan_v3($getChild, $listJenjangPerancang, $deep + 1, $getDeep, $jenjangPerancangId, $readonly, $addClass, $getOldName, $step + 1);
            }

        }

        return $html;

    }
}


if ( ! function_exists('persetujuan_dupak_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $jenjangPerancangId
     * @param int $readonly
     * @return string
     */
    function persetujuan_dupak_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $jenjangPerancangId, $readonly = 0) {

        $getDeep = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list;
        }

        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
            <thead>
            <tr>
            <th width="45%" colspan="'.$getDeep.'">'.__('general.butir_kegiatan').'</th>
            <th width="10%" class="text-center">'.__('general.date').'</th>
            <th width="15%" colspan="3" class="text-center">AK</th>
            <th width="10%" class="text-center">Satuan</th>
            <th width="15%" class="text-center">'.__('general.evidence').'</th>
            <th width="5%" class="text-center">'.__('general.action').'</th>
            </tr>
            </thead>
            <tbody>
            ';


        $html .= render_persetujuan_dupak_kegiatan_v3($ms_kegiatan, $listJenjangPerancangData, $deep, $getDeep, $jenjangPerancangId, $readonly);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_persetujuan_dupak_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $deep
     * @param $getDeep
     * @param $jenjangPerancangId
     * @param $readonly
     * @param string $parentClass
     * @param string $prevName
     * @return string
     */
    function render_persetujuan_dupak_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $deep, $getDeep, $jenjangPerancangId, $readonly, $parentClass = '', $prevName = '', $step = 0) {
        $html = '';

        foreach ($ms_kegiatan as $list) {

            $getId = $list['id'];
            $getName = $list['name'];
            $getAk = $list['ak'] > 0 ? $list['ak'] : '';
            $getSatuan = strlen($list['satuan']) > 0 ? $list['satuan'] : '';
            $getAction = '';
            $getChild = $list['have_child'] == 1 ? $list['child'] : [];
            $addClass = $parentClass.' kegiatan-'.$getId;
            $addLabel = '';

            $totalColspan = 0;
            $htmlColspan = '';
            $listIds = [];
            if ($getAk > 0) {

                if ($step > 1 && strlen($getName) <= 100 && strlen($prevName) > 0) {
                    $getName = $prevName.': <br/> &nbsp;'.$getName;
                }

                $getDataInput = $list['data'] ?? false;
                if ($getDataInput) {

                    $getName = '<label>'.$getName.'</label>';

                    $totalColspan = count($getDataInput);
                    foreach ($getDataInput as $listInput) {


                        $getKegId = $listInput['sp_kegiatan_id'];
                        $getKegiatanAk = $listInput['kredit'];
                        $getDokument = json_decode($listInput['dokument_pendukung'], true);
                        $getDokumentFisik = json_decode($listInput['dokument_fisik'], true);
                        //dd($getDokument);
                        $getListDokument = [];
                        $getListDokumentFisik = [];
                        if ($getDokument) {
                            foreach ($getDokument as $listDokument) {
                                $getListDokument[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                            }
                        }
                        if ($getDokumentFisik) {
                            foreach ($getDokumentFisik as $listDokument) {
                                $getListDokumentFisik[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                            }
                        }

                        //dd($getListDokument);
                        $listIds[] = [
                            'id' => $listInput['id'],
                            'tanggal' => date('d-M-Y', strtotime($listInput['tanggal'])),
                            'kredit_ak' => $getAk,
                            'kredit' => $getKegiatanAk,
                            'dokument' => $getListDokument,
                            'dokument_fisik' => $getListDokumentFisik,
                            'sp_kegiatan_id' => $getKegId,
                            'sp_kegiatan_message' => $listInput['sp_kegiatan_message'],
                            'sp_kegiatan_status' => $listInput['sp_kegiatan_status']
                        ];

                    }

                    if (count($listIds) > 1) {
                        $htmlColspan .= ' rowspan="'.count($listIds).'" ';
                    }

                }

                if (count($listIds) > 0) {
                    $addLabel = '<input type="hidden" id="kegiatan_hidden_'.$getId.'" value=\''.json_encode($listIds).'\'/>';
                }

            }

            $addHtmlTd = '';
            if ($deep > 0) {
                $addHtmlTd = str_repeat('<td'.$htmlColspan.' width="1%">&nbsp;</td>', $deep);
            }
            $newDeep = $getDeep - $deep;

            if ($deep == 0 || $getAk > 0) {

                $getListStatus = get_list_status_surat_pernyataan();

                if ($totalColspan <= 0) {

                    $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                        <td colspan="' . ($newDeep + 6) . '">' . $addLabel . $getName . '</td>
                        <td width="5%" class="text-center">' . $getAction . '</td>
                        </tr>';

                }
                else if ($totalColspan == 1) {

                    foreach ($listIds as $listDataKegiatan) {

                        $getKegId = $listDataKegiatan['sp_kegiatan_id'];
                        $getKegStatus = $listDataKegiatan['sp_kegiatan_status'];
                        $getKegMessage = $listDataKegiatan['sp_kegiatan_message'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';

                        if ($readonly == 1) {
                            $getAction = $getListStatus[$getKegStatus] ?? '-';
                            //dd($getAction);
                        }
                        else {
                            $setStatusOk = $getKegStatus == 80 ? ' checked' : '';
                            $setStatusCancel = $getKegStatus == 99 ? ' checked' : '';
                            $getAction = '<label><input type="radio" class="radio_button_ok" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_80" name="action_kegiatan['.$getKegId.']" value="80"'.$setStatusOk.'/> Disetujui</label>
                                <label><input type="radio" class="radio_button_cancel" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_99" name="action_kegiatan['.$getKegId.']" value="99"'.$setStatusCancel.'/> Ditolak</label>
                                <input type="hidden" class="message_kegiatan" id="message_kegiatan_'.$getKegId.'" name="message_kegiatan['.$getKegId.']" value="'.$getKegMessage.'">';
                        }

                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                            <td colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                            <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                            ' . $addHtmlAk . '
                            <td width="10%" class="text-center">' . $getSatuan . '</td>
                            <td width="15%">' . $getBukti . '</td>
                            <td width="5%" class="text-center">' . $getAction . '</td>
                            </tr>';

                    }

                }
                else {

                    foreach ($listIds as $indexing => $listDataKegiatan) {

                        $getKegId = $listDataKegiatan['sp_kegiatan_id'];
                        $getKegStatus = $listDataKegiatan['sp_kegiatan_status'];
                        $getKegMessage = $listDataKegiatan['sp_kegiatan_message'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';
                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        if ($readonly == 1) {
                            $getAction = $getListStatus[$getKegStatus] ?? '-';
                        }
                        else {
                            $setStatusOk = $getKegStatus == 80 ? ' checked' : '';
                            $setStatusCancel = $getKegStatus == 99 ? ' checked' : '';
                            $getAction = '<label><input type="radio" class="radio_button_ok" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_80" name="action_kegiatan['.$getKegId.']" value="80"'.$setStatusOk.'/> Disetujui</label>
                                <label><input type="radio" class="radio_button_cancel" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_99" name="action_kegiatan['.$getKegId.']" value="99"'.$setStatusCancel.'> Ditolak</label>
                                <input type="hidden" class="message_kegiatan" id="message_kegiatan_'.$getKegId.'" name="message_kegiatan['.$getKegId.']" value="'.$getKegMessage.'">';
                        }

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        if ($indexing == 0) {

                            $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                                <td' . $htmlColspan . ' colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                                <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                                ' . $addHtmlAk . '
                                <td' . $htmlColspan . ' width="10%" class="text-center">' . $getSatuan . '</td>
                                <td width="15%" class="text-center">' . $getBukti . '</td>
                                <td width="5%" class="text-center">' . $getAction . '</td>
                                </tr>';

                        } else {

                            $html .= '<tr class="all-row' . $addClass . '">
                                <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                                ' . $addHtmlAk . '
                                <td width="15%" class="text-center">' . $getBukti . '</td>
                                <td width="5%" class="text-center">' . $getAction . '</td>
                                </tr>';

                        }
                    }

                }

            }
            else {
                $deep -= 1;
            }

            $getOldName = $getName;

            if ($getChild) {
                $html .= render_persetujuan_dupak_kegiatan_v3($getChild, $listJenjangPerancang, $deep + 1, $getDeep, $jenjangPerancangId, $readonly, $addClass, $getOldName, $step + 1);
            }

        }

        return $html;

    }
}


if ( ! function_exists('persetujuan_pak_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $jenjangPerancangId
     * @param int $readonly
     * @return string
     */
    function persetujuan_pak_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $jenjangPerancangId, $readonly = 0) {

        $getDeep = set_deep_ms_kegiatan($ms_kegiatan);

        $listJenjangPerancangData = [];
        foreach ($listJenjangPerancang as $list) {
            $listJenjangPerancangData[$list->id] = $list;
        }

        $deep = 0;

        $html = '<table class="table table-kegiatan table-bordered table-striped">
            <thead>
            <tr>
            <th width="45%" colspan="'.$getDeep.'">'.__('general.butir_kegiatan').'</th>
            <th width="10%" class="text-center">'.__('general.date').'</th>
            <th width="15%" colspan="3" class="text-center">AK</th>
            <th width="10%" class="text-center">Satuan</th>
            <th width="15%" class="text-center">'.__('general.evidence').'</th>
            <th width="5%" class="text-center">'.__('general.action').'</th>
            </tr>
            </thead>
            <tbody>
            ';


        $html .= render_persetujuan_pak_kegiatan_v3($ms_kegiatan, $listJenjangPerancangData, $deep, $getDeep, $jenjangPerancangId, $readonly);

        $html .= '</tbody></table>';

        return $html;
    }
}

if ( ! function_exists('render_persetujuan_pak_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $listJenjangPerancang
     * @param $deep
     * @param $getDeep
     * @param $jenjangPerancangId
     * @param $readonly
     * @param string $parentClass
     * @param string $prevName
     * @return string
     */
    function render_persetujuan_pak_kegiatan_v3($ms_kegiatan, $listJenjangPerancang, $deep, $getDeep, $jenjangPerancangId, $readonly, $parentClass = '', $prevName = '', $step = 0) {
        $html = '';

        foreach ($ms_kegiatan as $list) {

            $getId = $list['id'];
            $getName = $list['name'];
            $getAk = $list['ak'] > 0 ? $list['ak'] : '';
            $getSatuan = strlen($list['satuan']) > 0 ? $list['satuan'] : '';
            $getAction = '';
            $getChild = $list['have_child'] == 1 ? $list['child'] : [];
            $addClass = $parentClass.' kegiatan-'.$getId;
            $addLabel = '';

            $totalColspan = 0;
            $htmlColspan = '';
            $listIds = [];
            if ($getAk > 0) {

                if ($step > 1 && strlen($getName) <= 100 && strlen($prevName) > 0) {
                    $getName = $prevName.': <br/> &nbsp;'.$getName;
                }

                $getDataInput = $list['data'] ?? false;
                if ($getDataInput) {

                    $getName = '<label>'.$getName.'</label>';

                    $totalColspan = count($getDataInput);
                    foreach ($getDataInput as $listInput) {


                        $getKegId = $listInput['sp_kegiatan_id'];
                        $getKegiatanAk = $listInput['kredit'];
                        $getDokument = json_decode($listInput['dokument_pendukung'], true);
                        $getDokumentFisik = json_decode($listInput['dokument_fisik'], true);
                        //dd($getDokument);
                        $getListDokument = [];
                        $getListDokumentFisik = [];
                        if ($getDokument) {
                            foreach ($getDokument as $listDokument) {
                                $getListDokument[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                            }
                        }
                        if ($getDokumentFisik) {
                            foreach ($getDokumentFisik as $listDokument) {
                                $getListDokumentFisik[] = [
                                    'url' => asset($listDokument['location']),
                                    'name' => $listDokument['name']
                                ];
                            }
                        }

                        //dd($getListDokument);
                        $listIds[] = [
                            'id' => $listInput['id'],
                            'tanggal' => date('d-M-Y', strtotime($listInput['tanggal'])),
                            'kredit_ak' => $getAk,
                            'kredit' => $getKegiatanAk,
                            'dokument' => $getListDokument,
                            'dokument_fisik' => $getListDokumentFisik,
                            'sp_kegiatan_id' => $getKegId,
                            'sp_kegiatan_message' => $listInput['sp_kegiatan_message'],
                            'sp_kegiatan_status' => $listInput['sp_kegiatan_status']
                        ];

                    }

                    if (count($listIds) > 1) {
                        $htmlColspan .= ' rowspan="'.count($listIds).'" ';
                    }

                }

                if (count($listIds) > 0) {
                    $addLabel = '<input type="hidden" id="kegiatan_hidden_'.$getId.'" value=\''.json_encode($listIds).'\'/>';
                }

            }

            $addHtmlTd = '';
            if ($deep > 0) {
                $addHtmlTd = str_repeat('<td'.$htmlColspan.' width="1%">&nbsp;</td>', $deep);
            }
            $newDeep = $getDeep - $deep;

            if ($deep == 0 || $getAk > 0) {

                $getListStatus = get_list_status_surat_pernyataan();

                if ($totalColspan <= 0) {

                    $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                        <td colspan="' . ($newDeep + 6) . '">' . $addLabel . $getName . '</td>
                        <td width="5%" class="text-center">' . $getAction . '</td>
                        </tr>';

                }
                else if ($totalColspan == 1) {

                    foreach ($listIds as $listDataKegiatan) {

                        $getKegId = $listDataKegiatan['sp_kegiatan_id'];
                        $getKegStatus = $listDataKegiatan['sp_kegiatan_status'];
                        $getKegMessage = $listDataKegiatan['sp_kegiatan_message'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';

                        if ($readonly == 1) {
                            $getAction = $getListStatus[$getKegStatus] ?? '-';
                            //dd($getAction);
                        }
                        else {
                            $setStatusOk = $getKegStatus == 80 ? ' checked' : '';
                            $setStatusCancel = $getKegStatus == 99 ? ' checked' : '';
                            $getAction = '<label><input type="radio" class="radio_button_ok" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_80" name="action_kegiatan['.$getKegId.']" value="80"'.$setStatusOk.'/> Disetujui</label>
                                <label><input type="radio" class="radio_button_cancel" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_99" name="action_kegiatan['.$getKegId.']" value="99"'.$setStatusCancel.'/> Ditolak</label>
                                <input type="hidden" class="message_kegiatan" id="message_kegiatan_'.$getKegId.'" name="message_kegiatan['.$getKegId.']" value="'.$getKegMessage.'">';
                        }

                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                            <td colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                            <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                            ' . $addHtmlAk . '
                            <td width="10%" class="text-center">' . $getSatuan . '</td>
                            <td width="15%">' . $getBukti . '</td>
                            <td width="5%" class="text-center">' . $getAction . '</td>
                            </tr>';

                    }

                }
                else {

                    foreach ($listIds as $indexing => $listDataKegiatan) {

                        $getKegId = $listDataKegiatan['sp_kegiatan_id'];
                        $getKegStatus = $listDataKegiatan['sp_kegiatan_status'];
                        $getKegMessage = $listDataKegiatan['sp_kegiatan_message'];
                        $getAk = $listDataKegiatan['kredit_ak'];
                        $getNewAk = $listDataKegiatan['kredit'];
                        $dokument = $listDataKegiatan['dokument'];
                        $dokument_fisik = $listDataKegiatan['dokument_fisik'];
                        $getBukti = 'Dokument Pendukung:<ul>';
                        foreach ($dokument as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        $getBukti .= 'Dokument Fisik:<ul>';
                        foreach ($dokument_fisik as $listDoc) {
                            $shortDocName = strlen($listDoc['name']) > 15 ? substr($listDoc['name'], 0, 15).'...' : $listDoc['name'];
                            $getBukti .= '<li><a href="'.$listDoc['url'].'" title="'.$listDoc['name'].'">'.$shortDocName.'</a></li>';
                        }
                        $getBukti .= '</ul>';

                        if ($readonly == 1) {
                            $getAction = $getListStatus[$getKegStatus] ?? '-';
                        }
                        else {
                            $setStatusOk = $getKegStatus == 80 ? ' checked' : '';
                            $setStatusCancel = $getKegStatus == 99 ? ' checked' : '';
                            $getAction = '<label><input type="radio" class="radio_button_ok" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_80" name="action_kegiatan['.$getKegId.']" value="80"'.$setStatusOk.'/> Disetujui</label>
                                <label><input type="radio" class="radio_button_cancel" data-id="'.$getKegId.'" id="action_kegiatan_'.$getKegId.'_99" name="action_kegiatan['.$getKegId.']" value="99"'.$setStatusCancel.'> Ditolak</label>
                                <input type="hidden" class="message_kegiatan" id="message_kegiatan_'.$getKegId.'" name="message_kegiatan['.$getKegId.']" value="'.$getKegMessage.'">';
                        }

                        if ($getAk != $getNewAk) {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">80%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }
                        else {
                            $addHtmlAk = '<td width="5%" class="text-center">'.$getAk.'</td>'.
                                '<td width="5%" class="text-center">100%</td>'.
                                '<td width="5%" class="text-center">'.$getNewAk.'</td>';
                        }

                        if ($indexing == 0) {

                            $html .= '<tr class="all-row' . $addClass . '">' . $addHtmlTd . '
                                <td' . $htmlColspan . ' colspan="' . $newDeep . '">' . $addLabel . $getName . '</td>
                                <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                                ' . $addHtmlAk . '
                                <td' . $htmlColspan . ' width="10%" class="text-center">' . $getSatuan . '</td>
                                <td width="15%" class="text-center">' . $getBukti . '</td>
                                <td width="5%" class="text-center">' . $getAction . '</td>
                                </tr>';

                        } else {

                            $html .= '<tr class="all-row' . $addClass . '">
                                <td width="10%" class="text-center">' . $listDataKegiatan['tanggal'] . '</td>
                                ' . $addHtmlAk . '
                                <td width="15%" class="text-center">' . $getBukti . '</td>
                                <td width="5%" class="text-center">' . $getAction . '</td>
                                </tr>';

                        }
                    }

                }

            }
            else {
                $deep -= 1;
            }

            $getOldName = $getName;

            if ($getChild) {
                $html .= render_persetujuan_pak_kegiatan_v3($getChild, $listJenjangPerancang, $deep + 1, $getDeep, $jenjangPerancangId, $readonly, $addClass, $getOldName, $step + 1);
            }

        }

        return $html;

    }
}




if ( ! function_exists('render_kegiatan_v3')) {
    /**
     * @param $ms_kegiatan
     * @param $jenjang_perancang_id
     * @param $listJenjangPerancang
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

            $getJenjangPerancang = isset($listJenjangPerancangData[$getMsKegiatan->jenjang_perancang_id]) ? $listJenjangPerancangData[$getMsKegiatan->jenjang_perancang_id] : '&nbsp;';

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
                $html .= '<td width="1%">&nbsp;</td>';
            }

            $html .= '
                <td colspan="'.($totalDeep - $deep).'">'.$name.'</td>
                <td width="5%">'.$ak.'</td>
                <td width="10%">'.$satuan.'</td>
                <td width="15%">'.$pelaksana.'</td>
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
