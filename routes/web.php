<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '/', 'middleware'=>['web']], function () use ($router){
    $router->get('login', ['uses'=>'Admin\AccessAdminController@getLogin', 'middleware'=>['have_login_admin']])->name('admin.login');
    $router->post('login', ['uses'=>'Admin\AccessAdminController@postLogin', 'middleware'=>['have_login_admin']])->name('admin.login.post');
    $router->get('logout', ['uses'=>'Admin\AccessAdminController@doLogout'])->name('admin.logout');
    $router->get('data-perancang', ['uses'=>'Admin\HomeController@dataPerancang'])->name('admin.dataPerancang');
    $router->get('register', ['uses'=>'Admin\HomeController@register'])->name('admin.get.register');
    $router->post('register', ['uses'=>'Admin\HomeController@postRegister'])->name('admin.post.register');
    $router->get('/', ['uses'=>'Admin\HomeController@checkData'])->name('admin.checkData');

    $router->group(['middleware' => ['admin_login', 'preventBackHistory']], function () use ($router) {
        $router->group(['prefix' => 'profile'], function () use ($router) {
            $router->get('edit', ['uses'=>'Admin\ProfileController@getProfile'])->name('admin.getProfile');
            $router->post('edit', ['uses'=>'Admin\ProfileController@postProfile'])->name('admin.postProfile');
            $router->get('password', ['uses'=>'Admin\ProfileController@getPassword'])->name('admin.getPassword');
            $router->post('password', ['uses'=>'Admin\ProfileController@postPassword'])->name('admin.postPassword');
        //    $router->get('dashboard', ['uses'=>'Admin\ProfileController@dashboard'])->name('admin.dashboard');
            $router->get('/', ['uses'=>'Admin\ProfileController@profile'])->name('admin.profile');
        });

        $router->group(['middleware' => ['admin_access_permission']], function () use ($router) {
            $listRouter = [
                'Admin\PermenController' => 'permen',
                'Admin\UserRegisteredController' => 'user-registered',
                'Admin\GenderController' => 'gender',
                'Admin\GolonganController' => 'golongan',
                'Admin\JabatanPerancangController' => 'jabatan-perancang',
                'Admin\JenjangPerancangController' => 'jenjang-perancang',
                'Admin\PendidikanController' => 'pendidikan',
                'Admin\UnitKerjaController' => 'unit-kerja',
                'Admin\MsKegiatanController' => 'ms-kegiatan',
//                'Admin\PermenMsKegiatanController' => 'permen-ms-kegiatan',
                'Admin\KegiatanController' => 'kegiatan',
                'Admin\StaffController' => 'staff',
                'Admin\SuratPernyataanController' => 'surat-pernyataan',
                'Admin\DupakController' => 'dupak',
                'Admin\BapakController' => 'bapak',

            ];
            foreach ($listRouter as $controller => $linkName) {
                switch ($linkName) {
                    case 'surat-pernyataan':
                        $router->get($linkName . '/verification', $controller . '@verification')->name('admin.' . $linkName . '.verification');
                        $router->get($linkName . '/ms-kegiatan/{id}', $controller . '@getKegiatan')->name('admin.' . $linkName . '.getKegiatan');
                        $router->post($linkName . '/{id}/approve', $controller . '@approve')->name('admin.' . $linkName . '.approve');
                        $router->post($linkName . '/{id}/reject', $controller . '@reject')->name('admin.' . $linkName . '.reject');
                        break;
                    case 'dupak':
                        $router->get($linkName . '/verification', $controller . '@verification')->name('admin.' . $linkName . '.verification');
                        $router->get($linkName . '/{id}/approve', $controller . '@approve')->name('admin.' . $linkName . '.approve');
                        $router->get($linkName . '/{id}/reject', $controller . '@reject')->name('admin.' . $linkName . '.reject');
                        $router->get($linkName . '/{id}/preview-pdf', $controller . '@previewPDF')->name('admin.' . $linkName . '.previewPDF');
                        $router->post($linkName . '/{id}/upload-dupak', $controller . '@uploadFile')->name('admin.' . $linkName . '.uploadFile');
                        $router->post($linkName . '/{id}/send-dupak', $controller . '@sendToSekretariat')->name('admin.' . $linkName . '.sendToSekretariat');
                        $router->get($linkName . '/{id}/cetak_kegiatan', $controller . '@cetakKegiatan')->name('admin.' . $linkName . '.cetakKegiatan');

                        break;
                    case 'staff':
                        $router->get($linkName . '/atasan', $controller . '@indexAtasan')->name('admin.' . $linkName . '.indexAtasan');
                        $router->get($linkName . '/perancang', $controller . '@indexPerancang')->name('admin.' . $linkName . '.indexPerancang');
                        $router->get($linkName . '/calon-perancang', $controller . '@indexCalonPerancang')->name('admin.' . $linkName . '.indexCalonPerancang');
                        $router->get($linkName . '/sekretariat', $controller . '@indexSekretariat')->name('admin.' . $linkName . '.indexSekretariat');
                        $router->get($linkName . '/tim-penilai', $controller . '@indexTimPenilai')->name('admin.' . $linkName . '.indexTimPenilai');
//                        $router->get($linkName . '/get', $controller . '@get')->name('admin.' . $linkName . '.get');
                        break;
                    case 'user-registered':
                        $router->get($linkName . '/approve/{id}', $controller . '@approved')->name('admin.' . $linkName . '.approved');
                        $router->get($linkName . '/reject/{id}', $controller . '@rejected')->name('admin.' . $linkName . '.rejected');
                        $router->get($linkName . '/create-staff/{id}', $controller . '@createStaff')->name('admin.' . $linkName . '.createStaff');
                        $router->put($linkName . '/create-staff/{id}', $controller . '@storeDataStaff')->name('admin.' . $linkName . '.storeDataStaff');
                        break;
                    case 'kegiatan':
                        $router->get($linkName . '/get-ms-kegiatan/{permen_id}', $controller . '@getMsKegiatan')->name('admin.' . $linkName . '.getMsKegiatan');
                        break;

                }
                $router->get($linkName . '/data', $controller . '@dataTable')->name('admin.' . $linkName . '.dataTable');
                $router->resource($linkName, $controller, ['as' => 'admin']);
            }

            $router->group(['prefix' => 'permen/{parent_id}'], function () use ($router) {
                $router->get('mskegiatan/data', 'Admin\PermenMsKegiatanController@dataTable')->name('admin.mskegiatan.dataTable');
                $router->resource('mskegiatan', 'Admin\PermenMsKegiatanController', ['as' => 'admin']);
            });
            $router->get('report', 'Admin\ReportController@index')->name('admin.report');

            //$router->get('portal', 'Admin\HomeController@portal')->name('admin.portal');
            $router->get('portal', ['uses'=>'Admin\HomeController@portal'])->name('admin.portal');
            $router->get('data-informasi', ['uses' => 'Admin\HomeController@dataInformasi'])->name('admin.dataInformasi');
        });

    });

});


Route::get('truncate', 'Admin\HomeController@truncate');
//Route::get('check-butir-kegiatan', 'Admin\HomeController@checkButirKegiatan');
Route::get('fixing-data', 'Admin\HomeController@fixingData');
