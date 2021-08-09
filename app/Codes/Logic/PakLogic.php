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
     * @param array $status
     * @return array
     */
    public function getKegiatanUser($userId, array $status = [])
    {
        $result = [];
        if (count($status) <= 0) {
            $status = [1];
        }

        $getKegiatan = Kegiatan::where('user_id', $userId)->whereIn('status', $status)->get();
        if ($getKegiatan) {
            $getJudul = [];
            $permenIds = [];
            $getKegiatanIds = [];

            foreach ($getKegiatan as $list) {
                $permenIds[] = $list->permen_id;
                $getKegiatanIds[] = $list->ms_kegiatan_id;
                $getJudul[$list->judul][] = $list->ms_kegiatan_id;
            }

            $getKegiatanIds = array_unique($getKegiatanIds);

            $getMsKegiatan = MsKegiatan::where('permen_id', $permenIds)->get();
            $temp = [];
            foreach ($getMsKegiatan->toArray() as $list) {
                $temp[$list['id']] = $list;
            }

            $result = $this->getParentTreeKegiatan($temp, $getKegiatanIds);

            $temp = [];
            foreach ($result as $list) {
                $temp[$list['id']] = $list;
            }
            $result = $temp;

            $temp = [];
            foreach ($result as $list) {
                $temp[$list['parent_id']][] = $list;
            }
            $result =  $temp;

        }

        return [
            'data' => $getKegiatan,
            'list_kegiatan' => $result
        ];

    }

    /**
     * @param $msKegiatan
     * @param $childIds
     * @return array
     */
    protected function getParentTreeKegiatan($msKegiatan, $childIds)
    {
        $result = [];

        foreach ($childIds as $childId) {
            if (isset($msKegiatan[$childId])) {
                $getMsKegiatan = $msKegiatan[$childId];
                if ($getMsKegiatan['parent_id'] > 0) {
                    $getParent = $this->checkParentTreeKegiatan($msKegiatan, $getMsKegiatan['parent_id']);
                    if (count($getParent) > 0) {
                        $result = array_merge($result, $getParent);
                    }
                }
                $result[] = $getMsKegiatan;
            }
        }

        return $result;
    }

    /**
     * @param $msKegiatan
     * @param $childId
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
            $result[] = $getMsKegiatan;
        }
        return $result;
    }

}
