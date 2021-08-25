<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Logic\_CrudController;
use App\Codes\Models\Instansi;
use Illuminate\Http\Request;
use App\Codes\Models\Users;
use App\Codes\Models\Golongan;
use App\Codes\Models\Pangkat;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\JenjangPerancang;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class PerancangController extends _CrudController
{
    public function __construct(Request $request)
    {

        $passingData = [
            'id' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0
            ],
            'name' => [
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'username' => [
                'extra' => [
                    'edit' => ['disabled' => true]
                ],
                'lang' => 'general.nip'
            ],
            'pangkat_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.pangkat'
            ],
            'golongan_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.golongan'
            ],
            'tmt_pangkat' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.kenaikan_jenjang_terakhir'
            ],
            'gelar_akademik' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select',
                'lang' => 'general.gelar_akademik'
            ],
            'tempat_lahir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.tempat_lahir',
            ],
            'tgl_lahir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tanggal_lahir',
            ],
            'gender' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select',
                'lang' => 'general.gender'
            ],
            'instansi_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.nama_instansi'
            ],
            'unit_kerja_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.unit_kerja'
            ],
            'kartu_pegawai' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.kartu_pegawai',
            ],
            'alamat_kantor' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.alamat_kantor',
            ],
            'email' => [
                'validation' => [
                    'edit' => 'required|email'
                ],
                'type' => 'email'
            ],
            'tahun_pelaksanaan_diklat' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tahun_pelaksanaan_diklat',
            ],
            'nomor_angkat_jabatan_pertama' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.nomor_angkat_jabatan_pertama',
            ],
            'tgl_angkat_jabatan_pertama' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tgl_angkat_jabatan_pertama',
            ],
            'nomor_keputusan_naik_pangkat_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.nomor_keputusan_naik_pangkat_terakhir',
            ],
            'tgl_keputusan_naik_pangkat_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tgl_keputusan_naik_pangkat_terakhir',
            ],
            'nomor_angkat_jabatan_akhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.angkat_jabatan_akhir',
            ],
            'tgl_angkat_jabatan_akhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tgl_angkat_jabatan_akhir',
            ],
            'nomor_keputusan_pemberhentian' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.nomor_keputusan_pemberhentian',
            ],
            'tgl_keputusan_pemberhentian' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tgl_keputusan_pemberhentian',
            ],
            'upline_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.atasan',
                'type' => 'select2'
            ],
            'masa_penilaian_ak_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.masa_penilaian_ak_terakhir',
            ],
            'tanggal_pak_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'datepicker',
                'lang' => 'general.tgl_pak_terakhir',
            ],
            'nomor_pak_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.nomor_pak_terakhir',
            ],
            'angka_kredit_terakhir' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'lang' => 'general.angka_kredit_terakhir',
            ],
            'jenjang_perancang_id' => [
                'validation' => [
                    'edit' => 'required'
                ],
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang'
            ],
            'status' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select'
            ],
            'action' => [
                'create' => 0,
                'edit' => 0,
                'show' => 0,
                'lang' => 'Aksi',
            ]
        ];

        parent::__construct(
            $request, 'general.perancang', 'perancang', 'Users', 'perancang',
            $passingData
        );

        $getGolongan = Golongan::where('status', 1)->pluck('name', 'id')->toArray();
        $listGolongan = [0 => 'Kosong'];
        if($getGolongan) {
            foreach($getGolongan as $key => $value) {
                $listGolongan[$key] = $value;
            }
        }

        $getJenjangPerancang = JenjangPerancang::where('status', 1)->pluck('name', 'id')->toArray();
        $listJenjangPerancang = [0 => 'Kosong'];
        if($getJenjangPerancang) {
            foreach($getJenjangPerancang as $key => $value) {
                $listJenjangPerancang[$key] = $value;
            }
        }

        $getPangkat = Pangkat::where('status', 1)->pluck('name', 'id')->toArray();
        $listPangkat = [0 => 'Kosong'];
        if($getPangkat) {
            foreach($getPangkat as $key => $value) {
                $listPangkat[$key] = $value;
            }
        }

        $getUnitKerja = UnitKerja::where('status', 1)->pluck('name', 'id')->toArray();
        $listUnitKerja = [0 => 'Kosong'];
        if($getUnitKerja) {
            foreach($getUnitKerja as $key => $value) {
                $listUnitKerja[$key] = $value;
            }
        }

        $getInstansi = Instansi::where('status', 1)->pluck('name', 'id')->toArray();
        $listInstansi = [0 => 'Kosong'];
        if($getInstansi) {
            foreach($getInstansi as $key => $value) {
                $listInstansi[$key] = $value;
            }
        }

        $this->data['listSet']['golongan_id'] = $listGolongan;
        $this->data['listSet']['jenjang_perancang_id'] = $listJenjangPerancang;
        $this->data['listSet']['pangkat_id'] = $listPangkat;
        $this->data['listSet']['unit_kerja_id'] = $listUnitKerja;
        $this->data['listSet']['instansi_id'] = $listInstansi;
        $this->data['listSet']['status'] = get_list_status();
        $this->data['listSet']['gelar_akademik'] = get_list_gelar();
        $this->data['listSet']['gender'] = get_list_gender();
        $this->data['listSet']['upline_id'] = Users::where('atasan', 1)->pluck('name', 'id')->toArray();

        $this->listView['index'] = env('ADMIN_TEMPLATE') . '.page.perancang.list';
        //$this->passingData = Users::where('role_id',3);
    }

    public function create()
    {
        $this->callPermission();

        $data = $this->data;

        $data['viewType'] = 'create';
        $data['formsTitle'] = __('general.title_create', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);

        $data['listSet']['upline_id'] = Users::where('atasan', 1)->pluck('name', 'id')->toArray();

        return view($this->listView[$data['viewType']], $data);
    }

    public function edit($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $data = $this->data;

        $data['viewType'] = 'edit';
        $data['formsTitle'] = __('general.title_edit', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        $data['listSet']['upline_id'] = Users::where('id', '!=', $id)->where('atasan', 1)->pluck('name', 'id')->toArray();

        return view($this->listView[$data['viewType']], $data);
    }

    public function show($id)
    {
        $this->callPermission();

        $getData = $this->crud->show($id);
        if (!$getData) {
            return redirect()->route($this->rootRoute.'.' . $this->route . '.index');
        }

        $data = $this->data;

        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.title_show', ['field' => $data['thisLabel']]);
        $data['passing'] = collectPassingData($this->passingData, $data['viewType']);
        $data['data'] = $getData;

        $data['listSet']['upline_id'] = Users::where('id', '!=', $id)->where('atasan', 1)->pluck('name', 'id')->toArray();

        return view($this->listView[$data['viewType']], $data);
    }

    public function dataTable()
    {
        $this->callPermission();

        //$userId = session()->get('admin_id');

        $dataTables = new DataTables();

        $builder = $this->model::query()->selectRaw('users.id, users.name, users.username as username, users.email, users.upline_id,
        users.tempat_lahir, users.tgl_lahir, users.kartu_pegawai, users.gender, C.name AS pangkat_id, D.name as golongan_id,
        E.name as jenjang_perancang_id, F.name as unit_kerja_id, G.name as instansi_id, B.name AS role,
        users.alamat_kantor, users.status, users.tmt_pangkat, users.tmt_jabatan, users.gelar_akademik,
        users.tahun_pelaksanaan_diklat, users.nomor_keputusan_naik_pangkat_terakhir, users.tgl_keputusan_naik_pangkat_terakhir,
        users.nomor_angkat_jabatan_akhir, users.tgl_angkat_jabatan_akhir, users.nomor_keputusan_pemberhentian, users.tgl_keputusan_pemberhentian,
        users.tanggal_pak_terakhir, users.nomor_pak_terakhir, users.nomor_angkat_jabatan_pertama, users.tgl_angkat_jabatan_pertama,
        users.masa_penilaian_ak_terakhir, users.angka_kredit_terakhir')
            ->where('users.perancang', '=', 1)
            ->leftJoin('role AS B', 'B.id', '=', 'users.role_id')
            ->leftJoin('pangkat AS C', 'C.id', '=', 'users.pangkat_id')
            ->leftJoin('golongan as D', 'D.id','=', 'users.golongan_id')
            ->leftJoin('jenjang_perancang as E','E.id','=','users.jenjang_perancang_id')
            ->leftJoin('unit_kerja as F','F.id','=','users.unit_kerja_id')
            ->leftJoin('instansi as G','G.id','=','users.instansi_id');



        $dataTables = $dataTables->eloquent($builder)
            ->addColumn('action', function ($query) {
                return view($this->listView['dataTable'], [
                    'query' => $query,
                    'thisRoute' => $this->route,
                    'permission' => $this->permission,
                    'masterId' => $this->masterId
                ]);
            });

        $listRaw = [];
        $listRaw[] = 'action';
        foreach (collectPassingData($this->passingData) as $fieldName => $list) {
            if (in_array($list['type'], ['select', 'select2'])) {
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName) {
                    $getList = isset($this->data['listSet'][$fieldName]) ? $this->data['listSet'][$fieldName] : [];
                    return isset($getList[$query->$fieldName]) ? $getList[$query->$fieldName] : $query->$fieldName;
                });
            } else if (in_array($list['type'], ['money'])) {
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return number_format($query->$fieldName, 0);
                });
            } else if (in_array($list['type'], ['image'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<img src="' . asset($list['path'] . $query->$fieldName) . '" class="img-responsive max-image-preview"/>';
                });
            } else if (in_array($list['type'], ['image_preview'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<img src="' . $query->$fieldName . '" class="img-responsive max-image-preview"/>';
                });
            } else if (in_array($list['type'], ['code'])) {
                $listRaw[] = $fieldName;
                $dataTables = $dataTables->editColumn($fieldName, function ($query) use ($fieldName, $list, $listRaw) {
                    return '<pre>' . json_encode(json_decode($query->$fieldName, true), JSON_PRETTY_PRINT) . '"</pre>';
                });
            } else if (in_array($list['type'], ['texteditor'])) {
                $listRaw[] = $fieldName;
            }
        }

        return $dataTables
            ->rawColumns($listRaw)
            ->make(true);
    }

    public function store(){
        $this->callPermission();


        $viewType = 'create';
        $this->request->validate([
            'username' => 'required',
            'name' => 'required',
            'email' => 'required',

        ]);
        $getUsername = $this->request->get('username');
        $getName = $this->request->get('name');
        $getEmail = $this->request->get('email');
        $getUpline = $this->request->get('upline_id');
        $getPangkat = $this->request->get('pangkat_id');
        $getGolongan = $this->request->get('golongan_id');
        $getTmtPangkat = $this->request->get('tmt_pangkat');
        $getGelarAkadademik = $this->request->get('gelar_akademik');
        $getJenjangPerancang = $this->request->get('jenjang_perancang_id');
        $getTanggalLahir = $this->request->get('tgl_lahir');
        $getTempatLahir = $this->request->get('tempat_lahir');
        $getTmtJabatan = $this->request->get('tmt_jabatan');
        $getUnitKerja = $this->request->get('unit_kerja_id');
        $getInstansi = $this->request->get('instansi_id');
        $getTahunPelaksanaanDiklat = $this->request->get('tahun_pelaksanaan_diklat');
        $getNomorAngkatJabatan = $this->request->get('nomor_angkat_jabatan_pertama');
        $getTglAngkatJabatan = $this->request->get('tgl_angkat_jabatan_pertama');
        $getNomorNaikPangkatAkhir = $this->request->get('nomor_keputusan_naik_pangkat_terakhir');
        $getTglNaikPangkatAkhir = $this->request->get('tgl_keputusan_naik_pangkat_terakhir');
        $getNomorKeputusanBerhenti = $this->request->get('nomor_keputusan_pemberhentian');
        $getTglKeputusanBerhenti = $this->request->get('tgl_keputusan_pemberhentian');
        $getNomorAngkatJabatanAkhir = $this->request->get('nomor_angkat_jabatan_Akhir');
        $getTglAngkatJabatanAkhir = $this->request->get('tgl_angkat_jabatan_akhir');
        $getTglPakTerakhir = $this->request->get('tanggal_pak_terakhir');
        $getNomorPakTerakhir = $this->request->get('nomor_pak_terakhir');
        $getMasaAkTerakhir = $this->request->get('masa_penilaian_ak_terakhir');
        $getPakTerakhir = $this->request->get('angka_kredit_terakhir');
        $getStatus = $this->request->get('status');
        $getGender = $this->request->get('gender');
        $getKartuPegawai = $this->request->get('kartu_pegawai');
        $getAlamatKantor = $this->request->get('alamat_kantor');


        $perancang = new Users();
        $perancang->name = $getName;
        $perancang->username = $getUsername;
        $perancang->email = $getEmail;
        $perancang->password = Hash::make('123');
        $perancang->upline_id =$getUpline;
        $perancang->pangkat_id = $getPangkat;
        $perancang->golongan_id = $getGolongan;
        $perancang->tmt_pangkat = $getTmtPangkat;
        $perancang->jenjang_perancang_id = $getJenjangPerancang;
        $perancang->gelar_akademik = $getGelarAkadademik;
        $perancang->tempat_lahir = $getTempatLahir;
        $perancang->tgl_lahir = $getTanggalLahir;
        $perancang->tmt_jabatan = $getTmtJabatan;
        $perancang->unit_kerja_id = $getUnitKerja;
        $perancang->instansi_id = $getInstansi;
        $perancang->tahun_pelaksanaan_diklat = $getTahunPelaksanaanDiklat;
        $perancang->nomor_angkat_jabatan_pertama = $getNomorAngkatJabatan;
        $perancang->tgl_angkat_jabatan_pertama = $getTglAngkatJabatan;
        $perancang->nomor_keputusan_naik_pangkat_terakhir = $getNomorNaikPangkatAkhir;
        $perancang->tgl_keputusan_naik_pangkat_terakhir = $getTglNaikPangkatAkhir;
        $perancang->nomor_angkat_jabatan_akhir = $getNomorAngkatJabatanAkhir;
        $perancang->tgl_angkat_jabatan_akhir = $getTglAngkatJabatanAkhir;
        $perancang->nomor_keputusan_pemberhentian = $getNomorKeputusanBerhenti;
        $perancang->tgl_keputusan_pemberhentian = $getTglKeputusanBerhenti;
        $perancang->tanggal_pak_terakhir = $getTglPakTerakhir;
        $perancang->nomor_pak_terakhir = $getNomorPakTerakhir;
        $perancang->masa_penilaian_ak_terakhir = $getMasaAkTerakhir;
        $perancang->angka_kredit_terakhir = $getPakTerakhir;
        $perancang->status = $getStatus;
        $perancang->gender = $getGender;
        $perancang->kartu_pegawai = $getKartuPegawai;
        $perancang->alamat_kantor = $getAlamatKantor;
        $perancang->role_id = 3;
        $perancang->perancang = 1;
        $perancang->save();

        if($this->request->ajax()){
            return response()->json(['result' => 1, 'message' => __('general.success_add')]);
        }
        else {
            session()->flash('message', __('general.success_add'));
            session()->flash('message_alert', 2);
            return redirect()->route('admin.' . $this->route . '.index');
        }


    }
}
