<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $request;
    protected $data = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function dataPerancang()
    {
        $data = $this->data;

        $getData = Users::selectRaw('unit_kerja_id, gender, unit_kerja.name AS unit_kerja_name, COUNT(*) AS total')
            ->join('unit_kerja', 'unit_kerja.id', 'users.unit_kerja_id', 'LEFT')
            ->where('perancang', 1)->whereIn('users.status', [1])
            ->groupByRaw('unit_kerja_id, gender, unit_kerja.name')
            ->orderBy('unit_kerja_id', 'ASC')->get();

        $temp1 = [];
        $temp2 = [];
        foreach ($getData as $list) {
            $temp1[$list->unit_kerja_id] = $list->unit_kerja_name;
            if(isset($temp2[$list->unit_kerja_id][$list->gender])) {
                $temp2[$list->unit_kerja_id][$list->gender] += $list->total;
            }
            else {
                $temp2[$list->unit_kerja_id][$list->gender] = $list->total;
            }
        }

        $temp = [];
        $totalData = 0;
        foreach ($temp1 as $unitKerjaId => $unitKerjaName) {
            $getPria = $temp2[$unitKerjaId][1] ?? 0;
            $getWanita = $temp2[$unitKerjaId][2] ?? 0;
            $total = $getPria + $getWanita;
            $totalData += $total;
            $temp[] = [
                'unit_kerja_id' => $unitKerjaId,
                'unit_kerja_name' => $unitKerjaName,
                'pria' => $getPria,
                'wanita' => $getWanita,
                'total' => $total
            ];
        }

        $getData = $temp;

        $data['data'] = $getData;
        $data['totalData'] = $totalData;

        return view(env('ADMIN_TEMPLATE').'.page.home.data_perancang', $data);
    }

}
