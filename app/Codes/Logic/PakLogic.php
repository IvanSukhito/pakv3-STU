<?php

namespace App\Codes\Logic;

use App\Codes\Models\Kegiatan;
use App\Codes\Models\MsKegiatan;

class PakLogic
{
    public function __construct()
    {
    }

    public function printKegiatanUser($userId, $status = 1)
    {
        $getKegiatan = Kegiatan::where('user_id', $userId)->where('status', $status)->get();
        $msPermenIds = [];
        $msKegiatanIds = [];
        foreach ($getKegiatan as $list) {
            $msKegiatanIds[] = $list->ms_kegiatan_id;
            $msPermenIds[] = $list->permen_id;
        }
        if (count($msKegiatanIds) > 0) {
            $getMsKegiatan = MsKegiatan::whereIn('permen_id', $msPermenIds)->get();
        }
    }

}
