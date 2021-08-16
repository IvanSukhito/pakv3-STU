<?php

namespace App\Codes\Logic;

use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;
use App\Codes\Models\Permen;

class PakLogic
{
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function createKegiatan()
    {
        $getPermen = Permen::where('status', 1)->get();
        $permenIds = [];
        $listPermen = [];
        foreach ($getPermen as $list) {
            $listPermen[$list->id] = $list->name;
            $permenIds[] = $list->id;
        }

        $getMsKegiatan = MsKegiatan::where('status', 1)->whereIn('permen_id', $permenIds)->get();
        $getMasterFilter = [];
        foreach ($getMsKegiatan as $list) {
            if ($list->parent_id <= 0) {
                $getMasterFilter[$list->id] = $list->name;
            }
        }

        return [
            'data' => $this->getCreateListTreeKegiatan($getMsKegiatan->toArray()),
            'permen' => $listPermen,
            'filter' => $getMasterFilter
        ];

    }

    /**
     * @param $msKegiatan
     * @param int $parentId
     * @return array
     */
    protected function getCreateListTreeKegiatan($msKegiatan, $parentId = 0)
    {
        $getTreeId = [];
        foreach ($msKegiatan as $list) {
            $getTreeId[$list['parent_id']][] = $list;
        }

        return $this->getCreateChildTreeKegiatan($getTreeId, $parentId);

    }

    /**
     * @param $msKegiatan
     * @param int $parentId
     * @return array
     */
    protected function getCreateChildTreeKegiatan($msKegiatan, $parentId = 0)
    {
        $result = [];
        if (isset($msKegiatan[$parentId])) {
            foreach ($msKegiatan[$parentId] as $list) {
                if (isset($msKegiatan[$list['id']])) {
                    $childs = $this->getCreateChildTreeKegiatan($msKegiatan, $list['id']);
                    $list['have_child'] = 1;
                    $list['childs'] = $childs;
                }
                else {
                    $list['have_child'] = 0;
                }
                $result[] = $list;
            }
        }

        return $result;

    }

    /**
     * @param $userId
     * @param null $getDateRange
     * @param array $status
     * @return array
     */
    public function getKegiatanUser($userId, $getDateRange = null, array $status = [])
    {
        if (count($status) <= 0) {
            $status = [1];
        }

        if ($getDateRange) {
            $getSplitDate = explode(' | ', $getDateRange);
            $getDateStart = date('Y-m-d', strtotime($getSplitDate[0]));
            $getDateEnd = isset($getSplitDate[1]) ? date('Y-m-d', strtotime($getSplitDate[1])) : date('Y-m-d', strtotime($getSplitDate[0]));

            $getKegiatan = Kegiatan::where('user_id', $userId)->whereIn('status', $status)
                ->where('tanggal', '>=', $getDateStart)->where('tanggal', '<=', $getDateEnd)
                ->orderBy('tanggal', 'ASC')->get();

        }
        else {
            $getKegiatan = Kegiatan::where('user_id', $userId)->whereIn('status', $status)->orderBy('tanggal', 'ASC')->get();
        }

        if ($getKegiatan) {
            $getJudul = [];
            $getDataKegiatan = [];
            $permenIds = [];
            $topIds = [];
            $totalAk = 0;
            $kredit = [];
         
            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $topIds[] = $list->top_id;
                $kredit[$list->top_id][] = $list->kredit;
                $getJudul[$list->permen_id][$list->top_id][$list->judul][] = $list->ms_kegiatan_id;
                $getDataKegiatan[$list->permen_id][$list->top_id][$list->judul][$list->ms_kegiatan_id][] = $list->toArray();
                $totalAk += $list->kredit;
            }

            if (count($permenIds) > 0) {
                $permenIds = array_unique($permenIds);
            }
            if (count($topIds) > 0) {
                $topIds = array_unique($topIds);
            }

            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $permenIds)->get();
            $temp = [];
            $getListTopKegiatan = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                if ($list['parent_id'] <= 0) {
                    $getListTopKegiatan[$list['id']] = $list;
                }
                $temp[$list['id']] = $list;    
            }

            $getMsKegiatan = $temp;

            $getPermen = Permen::whereIn('id', $permenIds)->get();
            $listPermen = [];
            foreach ($getPermen as $list) {
                $listPermen[$list->id] = $list->name;
            }

            return [
                'data' => $this->getParentTreeKegiatan($getMsKegiatan, $getJudul, $getDataKegiatan),
                'total_permen' => $permenIds,
                'total_top' => $topIds,
                'total_ak' => $totalAk,
                'permen' => $listPermen,
                'top_kegiatan' => $getListTopKegiatan,
                'kredit' => $kredit,
              
           
            ];

        }

        return [];

    }

    public function getSuratPernyataanUser($userId, $suratPernyataan)
    {
        $getKegiatan = Kegiatan::selectRaw('tx_kegiatan.*, tx_surat_pernyataan_kegiatan.id AS sp_kegiatan_id, tx_surat_pernyataan_kegiatan.message AS sp_kegiatan_message, tx_surat_pernyataan_kegiatan.status AS sp_kegiatan_status')
            ->join('tx_surat_pernyataan_kegiatan', 'tx_surat_pernyataan_kegiatan.kegiatan_id', '=', 'tx_kegiatan.id')
            ->where('tx_surat_pernyataan_kegiatan.surat_pernyataan_id', '=', $suratPernyataan->id)->get();

        if ($getKegiatan) {
            $getJudul = [];
            $getDataKegiatan = [];
            $permenIds = [];
            $topIds = [];
            $totalAk = 0;

            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $topIds[] = $list->top_id;
                $getJudul[$list->permen_id][$list->top_id][$list->judul][] = $list->ms_kegiatan_id;
                $getDataKegiatan[$list->permen_id][$list->top_id][$list->judul][$list->ms_kegiatan_id][] = $list->toArray();
                $totalAk += $list->kredit;
            }

            if (count($permenIds) > 0) {
                $permenIds = array_unique($permenIds);
            }
            if (count($topIds) > 0) {
                $topIds = array_unique($topIds);
            }

            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $permenIds)->get();
            $temp = [];
            $getListTopKegiatan = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                if ($list['parent_id'] <= 0) {
                    $getListTopKegiatan[$list['id']] = $list;
                }
                $temp[$list['id']] = $list;
            }

            $getMsKegiatan = $temp;

            $getPermen = Permen::whereIn('id', $permenIds)->get();
            $listPermen = [];
            foreach ($getPermen as $list) {
                $listPermen[$list->id] = $list->name;
            }

            return [
                'data' => $this->getParentTreeKegiatan($getMsKegiatan, $getJudul, $getDataKegiatan),
                'total_permen' => $permenIds,
                'total_top' => $topIds,
                'total_ak' => $totalAk,
                'permen' => $listPermen,
                'top_kegiatan' => $getListTopKegiatan
            ];

        }

        return [];
    }

    /**
     * @param $msKegiatan
     * @param $listJudulKegiatan
     * @param $dataKegiatan
     * @return array
     */
    protected function getParentTreeKegiatan($msKegiatan, $listJudulKegiatan, $dataKegiatan)
    {
        $result = [];

        foreach ($listJudulKegiatan as $getPermen => $listTopKegiatan) {
            foreach ($listTopKegiatan as $getTop => $listJudul) {
                foreach ($listJudul as $getJudul => $listKegiatan) {
                    $getAllKegiatanId = [];
                    foreach ($listKegiatan as $kegiatanId) {
                        $getListParent = $this->checkParentTreeKegiatan($msKegiatan, $kegiatanId);
                        $getAllKegiatanId = array_merge($getAllKegiatanId, $getListParent);
                    }
                    $getAllKegiatanId = array_unique($getAllKegiatanId);
                    sort($getAllKegiatanId);
                    $tempKegiatan = [];
                    foreach ($getAllKegiatanId as $kegId) {
                        if (isset($msKegiatan[$kegId])) {
                            $showDataKegiatan = isset($dataKegiatan[$getPermen][$getTop][$getJudul][$kegId]) ? $dataKegiatan[$getPermen][$getTop][$getJudul][$kegId] : false;
                            $temp = $msKegiatan[$kegId];
                            if ($showDataKegiatan) {
                                $temp['data'] = $showDataKegiatan;
                            }

                            $tempKegiatan[] = $temp;

                        }
                    }

                    $result[$getPermen][$getTop][$getJudul] = $this->getCreateListTreeKegiatan($tempKegiatan);

                }
            }
        }

        return $result;

    }

    /**
     * @param $msKegiatan
     * @param $childId
     * @param $fromId
     * @return array
     */
    protected function checkParentTreeKegiatan($msKegiatan, $childId)
    {
        $result = [];
        if (isset($msKegiatan[$childId])) {
            $getMsKegiatan = $msKegiatan[$childId];
            if ($getMsKegiatan['parent_id'] > 0) {
                $getParent = $this->checkParentTreeKegiatan($msKegiatan, $getMsKegiatan['parent_id']);
                $result = $getParent;
            }
            $result[] = $getMsKegiatan['id'];
        }
        return $result;
    }

}
