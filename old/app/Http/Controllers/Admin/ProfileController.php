<?php

namespace App\Http\Controllers\Admin;

use App\Codes\Models\Gender;
use App\Codes\Models\Golongan;
use App\Codes\Models\JabatanPerancang;
use App\Codes\Models\JenjangPerancang;
use App\Codes\Models\Pendidikan;
use App\Codes\Models\SeluruhPak;
use App\Codes\Models\Staffs;
use App\Codes\Models\UnitKerja;
use App\Codes\Models\Users;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $data;
    protected $request;

    public $passingData1;
    public $passingData2;
    public $passingData3;
    public $passingData4;
    public $passingData5;
    public $passingData6;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = [];

        $get_gender = [0 => 'Semua'];
        $temp = Gender::where('status', 1)->pluck('name', 'id');
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_gender[$id] = $name;
            }
        }
        $get_jenjang_perancang = [
            -1 => 'Semua',
            0 => 'Tidak Ditemukan'
        ];
        $temp = JenjangPerancang::where('status', 1)->pluck('name', 'id');
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_jenjang_perancang[$id] = $name;
            }
        }
        $get_unit_kerja = [0 => 'Semua'];
        $temp = UnitKerja::where('status', 1)->pluck('name', 'id');
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_unit_kerja[$id] = $name;
            }
        }
        $get_status = [-1 => 'Semua'];
        $temp = get_list_status();
        if ($temp) {
            foreach ($temp as $id => $name) {
                $get_status[$id] = $name;
            }
        }

        $this->data['list_gender'] = $get_gender;
        $this->data['list_jenjang_perancang'] = $get_jenjang_perancang;
        $this->data['list_unit_kerja'] = $get_unit_kerja;
        $this->data['list_status'] = $get_status;

        $this->passingData1 = generatePassingData([

            'perancang' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'atasan' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'sekretariat' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'tim_penilai' => [
                'type' => 'checkbox',
                'class' => 'setChecklistData'
            ],
            'status' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
                'type' => 'select'
            ],
        ]);
        $this->passingData2 = generatePassingData([
            'name' => [
                'validate' => [
                    'create' => 'required',
                    'edit' => 'required'
                ],
            ],
            'email' => [
                'validate' => [
                    'create' => 'email',
                    'edit' => 'email'
                ],
                'classParent' => 'hideAll perancang'
            ],
            'kartu_pegawai' => [
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],

        ]);
        $this->passingData3 = generatePassingData([
            'address' => [
                'lang' => 'Alamat Kantor',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'gender_id' => [
                'type' => 'select',
                'lang' => 'Jenis Kelamin',
                'classParent' => 'hideAll perancang atasan',
                'create' => 0,
            ],
            'birthdate' => [
                'type' => 'datepicker',
                'lang' => 'Tanggal Lahir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'pob' => [
                'lang' => 'Tempat Lahir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
        ]);
        $this->passingData4 = generatePassingData([
            'jabatan_perancang_id' => [
                'type' => 'select2',
                'lang' => 'Pangkat',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'golongan_id' => [
                'type' => 'select2',
                'lang' => 'Golongan',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tmt_golongan' => [
                'type' => 'datepicker',
                'lang' => 'TMT Kenaikan Jenjang Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'kenaikan_jenjang_terakhir' => [
                'type' => 'datepicker',
                'lang' => 'Kenaikan Jenjang Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'staff_id' => [
                'type' => 'select2',
                'lang' => 'Atasan',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'sekertariat_id' => [
                'type' => 'select2',
                'lang' => 'Sekretariat',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'penilai_id' => [
                'type' => 'select2',
                'lang' => 'Tim Penilai',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'jenjang_perancang_id' => [
                'type' => 'select2',
                'lang' => 'general.jenjang_perancang',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'pendidikan_id' => [
                'type' => 'select2',
                'lang' => 'general.pendidikan',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'unit_kerja_id' => [
                'type' => 'select2',
                'lang' => 'general.nama_instansi',
                'classParent' => 'hideAll perancang atasan',
                'create' => 0,
            ],
            'unit' => [
                'create' => 0,
                'lang'  => 'general.unit_kerja',
            ],
        ]);
        $this->passingData5 = generatePassingData([
            'masa_penilaian_terkahir_start' => [
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'masa_penilaian_terkahir_end' => [
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tanggal' => [
                'lang' => 'Tanggal PAK Terakhir',
                'type' => 'datepicker',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'mk_golongan_baru_bulan' => [
                'lang' => 'Masa kerja golongan baru Bulan',
                'type' => 'text',
                'create' => 0,
            ],
            'mk_golongan_baru_tahun' => [
                'lang' => 'Masa kerja golongan baru Tahun',
                'type' => 'text',
                'create' => 0,
            ],
            'mk_golongan_lama_bulan' => [
                'lang' => 'Masa kerja golongan lama Bulan',
                'type' => 'text',
                'create' => 0,
            ],
            'mk_golongan_lama_tahun' => [
                'lang' => 'Masa kerja golongan lama Tahun',
                'type' => 'text',
                'create' => 0,
            ],
            'angka_kredit' => [
                'lang' => 'Angka Kredit Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
                'type' => 'number'
            ],
            'nomor_pak' => [
                'lang' => 'Nomor PAK Terakhir',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'tahun_pelaksaan_diklat' => [
                'lang' => 'Isi Tahun Pelaksanaan Diklat',
                'type' => 'dateyear',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],

            'tahun_diangkat' => [
                'lang' => 'Tahun Diangkat',
                'type' => 'dateyear',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
            'status_diangkat' => [
                'lang' => 'Alasan',
                'type' => 'select',
                'classParent' => 'hideAll perancang',
                'create' => 0,
            ],
        ]);
        $this->passingData6 = generatePassingData([
            'username' => [
                'validate' => [
                    'create' => 'required|min:3',
                    'edit' => 'required|min:3'
                ],
                'lang' => 'Username/NIP'
            ],
            'password' => [
                'validate' => [
                    'create' => 'required',
                ],
                'show' => 0,
                'edit' => 1,
                'type' => 'password'
            ],
        ]);

        $this->data['listSet']['jabatan_perancang_id'] = JabatanPerancang::pluck('name', 'id')->toArray();
        $this->data['listSet']['staff_id'] = Staffs::where('atasan', 1)->pluck('name', 'user_id')->toArray();
        $this->data['listSet']['sekertariat_id'] = Staffs::where('sekretariat', 1)->pluck('name', 'user_id')->toArray();
        $this->data['listSet']['penilai_id'] = Staffs::where('tim_penilai', 1)->pluck('name', 'user_id')->toArray();
        $this->data['listSet']['golongan_id'] = Golongan::pluck('name', 'id')->toArray();
        $this->data['listSet']['pendidikan_id'] = Pendidikan::pluck('name', 'id')->toArray();
        $this->data['listSet']['unit_kerja_id'] = UnitKerja::pluck('name', 'id')->toArray();
        $this->data['listSet']['jenjang_perancang_id'] = JenjangPerancang::pluck('name', 'id')->toArray();
        $this->data['listSet']['gender_id'] = Gender::pluck('name', 'id')->toArray();
        $this->data['listSet']['status'] = get_list_status();
        $this->data['listSet']['status_diangkat'] = get_list_status_diangkat();
    }

    public function profile()
    {
        $data = $this->data;
        $admin_id = session()->get(env('APP_NAME').'admin_id');
        $getStaff = Staffs::where('user_id', $admin_id)->first();

        $get_data = Users::where('id', $admin_id)->first();


        $getBawahan = Staffs::where('staff_id', $get_data->id)->get();
        $getPerancang = Staffs::where('perancang', 1)->orderBy('id', 'ASC')->get();

        $data['data'] = $get_data;
        $data['getBawahan'] = $getBawahan;
        $data['getPerancang'] = $getPerancang;
        $data['viewType'] = 'show';
        $data['formsTitle'] = __('general.profile');
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisRoute'] = 'profile';
        $data['passing'] = generatePassingData([
            'name' => [],
            'username' => []
        ]);

        $data['passing1'] = collectPassingData($this->passingData1, $data['viewType']);
        $data['passing2'] = collectPassingData($this->passingData2, $data['viewType']);
        $data['passing3'] = collectPassingData($this->passingData3, $data['viewType']);
        $data['passing4'] = collectPassingData($this->passingData4, $data['viewType']);
        $data['passing5'] = collectPassingData($this->passingData5, $data['viewType']);
        $data['passing6'] = collectPassingData($this->passingData6, $data['viewType']);

        foreach ($getStaff->toArray() as $key => $value) {
            if (in_array($key, ['atasan', 'perancang', 'sekretariat', 'tim_penilai', 'top', 'address', 'kartu_pegawai',
                'birthdate', 'pob', 'masa_penilaian', 'nomor_pak', 'tanggal', 'tanggal_diangkat', 'angka_kredit',
                'kenaikan_jenjang_terakhir', 'masa_penilaian_terkahir_start', 'masa_penilaian_terkahir_end', 'alasan',
                'tmt_golongan', 'tahun_pelaksaan_diklat', 'tahun_diangkat', 'gender_id', 'jabatan_perancang_id',
                'golongan_id', 'pendidikan_id', 'unit_kerja_id', 'jenjang_perancang_id', 'pendidikan_id', 'status_diangkat',
                'staff_id', 'file_sk_pengangkatan_perancang', 'file_sk_terakhir_perancang', 'file_kartu_pegawai',
                'file_ijazah', 'file_sttpl', 'sk_sekretariat', 'sk_tim_penilai', 'unit', 'mk_golongan_baru_bulan',
                'mk_golongan_baru_tahun','mk_golongan_lama_bulan', 'mk_golongan_lama_tahun', 'penilai_id', 'sekertariat_id'])) {
                $data['data'][$key] = $value;
            }
        }

        return view(env('ADMIN_TEMPLATE').'.page.profile.show', $data);
    }

    public function getProfile()
    {

        $admin_id = session()->get(env('APP_NAME').'admin_id');
        $get_data = Users::where('id', $admin_id)->first();

        $getStaff = Staffs::where('user_id', $admin_id)->first();

        $getSeluruhPak = SeluruhPak::where('user_id', $admin_id)->get();

        $data = $this->data;

        $data['data'] = $get_data;
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisRoute'] = 'profile';
        $data['viewType'] = 'edit';

        $data['passing1'] = collectPassingData($this->passingData1, $data['viewType']);
        $data['passing2'] = collectPassingData($this->passingData2, $data['viewType']);
        $data['passing3'] = collectPassingData($this->passingData3, $data['viewType']);
        $data['passing4'] = collectPassingData($this->passingData4, $data['viewType']);
        $data['passing5'] = collectPassingData($this->passingData5, $data['viewType']);
        $data['passing6'] = collectPassingData($this->passingData6, $data['viewType']);

        //User information
        $data['data'] = $get_data;

        $data['seluruhPak'] = $getSeluruhPak;

        // Staff information
        foreach ($getStaff->toArray() as $key => $value) {
            if (in_array($key, ['atasan', 'perancang', 'sekretariat', 'tim_penilai', 'top', 'address', 'kartu_pegawai',
                'birthdate', 'pob', 'masa_penilaian', 'nomor_pak', 'tanggal', 'tanggal_diangkat', 'angka_kredit',
                'kenaikan_jenjang_terakhir', 'masa_penilaian_terkahir_start', 'masa_penilaian_terkahir_end', 'alasan',
                'tmt_golongan', 'tahun_pelaksaan_diklat', 'tahun_diangkat', 'gender_id', 'jabatan_perancang_id',
                'golongan_id', 'pendidikan_id', 'unit_kerja_id', 'jenjang_perancang_id', 'pendidikan_id', 'status_diangkat',
                'staff_id', 'file_sk_pengangkatan_perancang', 'file_sk_terakhir_perancang', 'file_kartu_pegawai',
                'file_ijazah', 'file_sttpl', 'sk_sekretariat', 'sk_tim_penilai', 'unit', 'mk_golongan_baru_bulan',
                'mk_golongan_baru_tahun','mk_golongan_lama_bulan', 'mk_golongan_lama_tahun', 'penilai_id', 'sekertariat_id'])) {
                $data['data'][$key] = $value;
            }
        }

        return view(env('ADMIN_TEMPLATE').'.page.profile.edit', $data);
    }

    public function postProfile()
    {
        $admin_id = session()->get(env('APP_NAME').'admin_id');

        $validator = [
            'username' => 'required|unique:users,username,'.$admin_id,
            'name' => 'required',
            'status' => 'required',
            'email' => 'required',
            'address' => '',
            'staff_id' => 'required',
            'gender_id' => 'required',
            'kartu_pegawai' => '',
            'birthdate' => '',
            'jenjang_perancang_id' => 'required',
            'tmt_golongan' => '',
            'pendidikan_id' => 'required',
            'kenaikan_jenjang_terakhir' => '',
            'unit_kerja_id' => '',
            'masa_penilaian_terkahir_start' => '',
            'masa_penilaian_terkahir_end' => '',
            'tanggal' => '',
            'tahun_pelaksaan_diklat' => '',
            'tahun_diangkat' => '',
            'angka_kredit' => 'required',
            'status_diangkat' => 'required',
            'nomor_pak' => '',
            'unit' => '',
        ];

//        dd($this->request->all());

        $data = $this->validate($this->request, $validator);

        $fileSkP = $this->request->file('sk_pengangkatan_perancang');
        $fileStp = $this->request->file('sk_terakhir_perancang');
        $fileKp = $this->request->file('kartu_pegawai');
        $fileIjazah = $this->request->file('ijazah');
        $fileSttp = $this->request->file('sttpl');

        $fileSekretariat = $this->request->file('sk_sekretariat');
        $fileTimPenilai = $this->request->file('sk_tim_penilai');

        session()->put(env('APP_NAME').'admin_name', $data['name']);

        $getData = Users::where('id', $admin_id)->first();
        foreach ($validator as $key => $value) {
            if (in_array($key, ['username', 'email', 'name', 'status']))
            {
                $getData->$key = $this->request->get($key);
            }
        }

        $getData->save();

        $time = date("Y-m-d" , time());

//        $updateUserStaff = Staffs::where([
//            'user_id' => $getData->id
//        ]);

        $updateUserStaff = Staffs::where('user_id', $admin_id)->first();

        $destinationPath =  './uploads/register/';
        $nameFileSkp = '';
        $nameFileStp = '';
        $nameFileKp = '';
        $nameFileIjazah = '';
        $nameFileSttp = '';

        $nameFileSekretariat = '';
        $nameFileTimPenilai = '';

        if ($fileSkP) {
            $getFileName = $fileSkP->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileSkp = create_slugs($updateUserStaff->name) . '-'. 'skp' .$time . '.' . $ext;
            $fileSkP->move($destinationPath, $nameFileSkp);
        }
        if ($fileStp) {
            $getFileName = $fileStp->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileStp = create_slugs($updateUserStaff->name) . '-'. 'stp' .$time . '.' . $ext;
            $fileStp->move($destinationPath, $nameFileStp);
        }
        if ($fileKp) {
            $getFileName = $fileKp->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileKp = create_slugs($updateUserStaff->name) . '-'. 'kp' .$time . '.' . $ext;
            $fileKp->move($destinationPath, $nameFileKp);
        }
        if ($fileIjazah) {
            $getFileName = $fileIjazah->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileIjazah = create_slugs($updateUserStaff->name) . '-'. 'ijazah' .$time . '.' . $ext;
            $fileIjazah->move($destinationPath, $nameFileIjazah);
        }
        if ($fileSttp) {
            $getFileName = $fileSttp->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileSttp = create_slugs($updateUserStaff->name) . '-'. 'sttp' .$time . '.' . $ext;
            $fileSttp->move($destinationPath, $nameFileSttp);
        }

        if ($fileSekretariat) {
            $getFileName = $fileSekretariat->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileSekretariat = create_slugs($updateUserStaff->name) . '-'. 'sekretariat' .$time . '.' . $ext;
            $fileSekretariat->move($destinationPath, $nameFileSekretariat);
        }
        if ($fileTimPenilai) {
            $getFileName = $fileTimPenilai->getClientOriginalName();
            $ext = explode('.', $getFileName);
            $ext = end($ext);
            $nameFileTimPenilai = create_slugs($updateUserStaff->name) . '-'. 'penilai' .$time . '.' . $ext;
            $fileTimPenilai->move($destinationPath, $nameFileTimPenilai);
        }

//        $updateUserStaff->top = intval($this->request->get('top')) == 1 ? 1 : 0;
//        $updateUserStaff->perancang = intval($this->request->get('perancang')) == 1 ? 1 : 0;
//        $updateUserStaff->atasan = intval($this->request->get('atasan')) == 1 ? 1 : 0;
//        $updateUserStaff->sekretariat = intval($this->request->get('sekretariat')) == 1 ? 1 : 0;
//        $updateUserStaff->tim_penilai = intval($this->request->get('tim_penilai')) == 1 ? 1 : 0;
        $updateUserStaff->staff_id = $this->request->get('staff_id');
        $updateUserStaff->sekertariat_id = intval($this->request->get('sekertariat_id'));
        $updateUserStaff->penilai_id = intval($this->request->get('penilai_id'));
        $updateUserStaff->address = $this->request->get('address');
        $updateUserStaff->gender_id = $this->request->get('gender_id');
        $updateUserStaff->kartu_pegawai = $this->request->get('kartu_pegawai');
        $updateUserStaff->birthdate = strtotime($this->request->get('birthdate')) > 10000 ? date('Y-m-d', strtotime($this->request->get('birthdate'))) : null;

        $updateUserStaff->pob = $this->request->get('pob');
        $updateUserStaff->jabatan_perancang_id = $this->request->get('jabatan_perancang_id');
        $updateUserStaff->nomor_pak = $this->request->get('nomor_pak');
        $updateUserStaff->golongan_id = $this->request->get('golongan_id');
        $updateUserStaff->kartu_pegawai = $this->request->get('kartu_pegawai');
        $updateUserStaff->jenjang_perancang_id = $this->request->get('jenjang_perancang_id');
        $updateUserStaff->tmt_golongan = strtotime($this->request->get('tmt_golongan')) > 10000 ? date('Y-m-d', strtotime($this->request->get('tmt_golongan'))) : null;
        $updateUserStaff->unit = $this->request->get('unit');
        $updateUserStaff->pendidikan_id = $this->request->get('pendidikan_id');
        $updateUserStaff->kenaikan_jenjang_terakhir = strtotime($this->request->get('kenaikan_jenjang_terakhir')) > 10000 ? date('Y-m-d', strtotime($this->request->get('kenaikan_jenjang_terakhir'))) : null;
        $updateUserStaff->unit_kerja_id = $this->request->get('unit_kerja_id');
        $updateUserStaff->masa_penilaian_terkahir_start = strtotime($this->request->get('masa_penilaian_terkahir_start')) > 10000 ? date('Y-m-d', strtotime($this->request->get('masa_penilaian_terkahir_start'))) : null;
        $updateUserStaff->masa_penilaian_terkahir_end = strtotime($this->request->get('masa_penilaian_terkahir_end')) > 10000 ? date('Y-m-d', strtotime($this->request->get('masa_penilaian_terkahir_end'))) : null;
        $updateUserStaff->mk_golongan_baru_bulan = $this->request->get('mk_golongan_baru_bulan');
        $updateUserStaff->mk_golongan_baru_tahun = $this->request->get('mk_golongan_baru_tahun');
        $updateUserStaff->mk_golongan_lama_bulan = $this->request->get('mk_golongan_lama_bulan');
        $updateUserStaff->mk_golongan_lama_tahun = $this->request->get('mk_golongan_lama_tahun');

        $updateUserStaff->masa_penilaian_terkahir_end = strtotime($this->request->get('tanggal')) > 10000 ? date('Y-m-d', strtotime($this->request->get('tanggal'))) : null;
        $updateUserStaff->tahun_pelaksaan_diklat = intval($this->request->get('tahun_pelaksaan_diklat'));
        $updateUserStaff->tahun_diangkat = intval($this->request->get('tahun_diangkat'));
        $updateUserStaff->angka_kredit = $this->request->get('angka_kredit') * 1;
        $updateUserStaff->status_diangkat = $this->request->get('status_diangkat');
        $updateUserStaff->register_id = $getData->id;

        if(strlen($nameFileSkp) > 2) {
            $updateUserStaff->file_sk_pengangkatan_perancang = $nameFileSkp;
        }
        if(strlen($nameFileStp) > 2) {
            $updateUserStaff->file_sk_terakhir_perancang = $nameFileStp;
        }
        if(strlen($nameFileKp) > 2) {
            $updateUserStaff->file_kartu_pegawai = $nameFileKp;
        }
        if(strlen($nameFileIjazah) > 2) {
            $updateUserStaff->file_ijazah = $nameFileIjazah;
        }
        if(strlen($nameFileSttp) > 2) {
            $updateUserStaff->file_sttpl = $nameFileSttp;
        }

        if(strlen($nameFileSekretariat) > 2) {
            $updateUserStaff->sk_sekretariat = $nameFileSekretariat;
        }
        if(strlen($nameFileTimPenilai) > 2) {
            $updateUserStaff->sk_tim_penilai = $nameFileTimPenilai;
        }

        $updateUserStaff->save();

        $id = $updateUserStaff->id;

        if($updateUserStaff->jenjang_perancang_id != 6){
            $updateUserStaff->register_id = $id.'-'. $updateUserStaff->gender_id.'-'. $updateUserStaff->tahun_diangkat;
        }

        if($this->request->file('seluruh_pak')) {
            $files = [];
            foreach ($this->request->file('seluruh_pak') as $file) {
                if ($file->isValid()) {

                    $extension = $file->getClientOriginalExtension();
                    $name = $file->getClientOriginalName();
                    $time = date("Y-m-d", time());
                    $fileName = create_slugs($updateUserStaff->name) . '-' . $name . '-seluruh_pak-' . $time . '.' . $extension;
                    $file->move($destinationPath, $fileName);

                    $files[] = [
                        'user_id' => $getData->id,
                        'file' => $fileName,
                        'created_at' => $now = Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => $now,
                    ];
                }
            }
            SeluruhPak::insert($files);
        }

        session()->flash('message', __('general.success_update'));
        session()->flash('message_alert', 2);

        return redirect()->route('admin.profile');
    }

    public function getPassword()
    {
        $admin_id = session()->get(env('APP_NAME').'admin_id');
        $data = $this->data;
        $get_data = Users::where('id', $admin_id)->first();

        $data['data'] = $get_data;
        $data['formsTitle'] = __('general.title_edit', ['field' => __('general.password') . ' ' . $get_data->name]);
        $data['thisLabel'] = __('general.title_show', ['field' => __('general.profile') . ' ' . $get_data->name]);
        $data['thisRoute'] = 'profile';
        $data['viewType'] = 'edit';
        $data['passing'] = generatePassingData([
            'old_password' => [
                'type' => 'password',
                'validation' => [
                    'edit' => 'required'
                ]
            ],
            'password' => [
                'type' => 'password',
                'validation' => [
                    'edit' => 'required|confirmed'
                ]
            ],
            'password_confirmation' => [
                'type' => 'password',
                'validation' => [
                    'edit' => 'required'
                ]
            ]
        ]);

        return view(env('ADMIN_TEMPLATE').'.page.profile.password', $data);

    }

    public function postPassword(Request $request)
    {
        $admin_id = session()->get(env('APP_NAME').'admin_id');

        $data = $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $account = Users::where('id', $admin_id)->first();
        if(!$account) {
            return redirect()->route('admin.profile');
        }

        if(!app('hash')->check($data['old_password'], $account->password)) {
            return redirect()->back()->withInput()->withErrors(
                [
                    'password' => __('general.error_old_password')
                ]
            );
        }

        $getData = Users::where('id', $admin_id)->first();
        $getData->password = app('hash')->make($data['password']);
        $getData->save();

        session()->flash('message', __('general.success_update'));
        session()->flash('message_alert', 2);

        return redirect()->route('admin.profile');
    }





}
