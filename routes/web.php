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
            $router->get('edit', ['uses'=>'Admin\ProfileController@getProfile'])->name('admin.get_profile');
            $router->post('edit', ['uses'=>'Admin\ProfileController@postProfile'])->name('admin.post_profile');
            $router->get('password', ['uses'=>'Admin\ProfileController@getPassword'])->name('admin.get_password');
            $router->post('password', ['uses'=>'Admin\ProfileController@postPassword'])->name('admin.post_password');
            $router->get('/', ['uses'=>'Admin\ProfileController@profile'])->name('admin.profile');
        });

//        $router->group(['prefix' => 'profile'], function () use ($router) {
//            $router->get('edit', ['uses'=>'Admin\ProfileController@getProfile'])->name('admin.getProfile');
//            $router->post('edit', ['uses'=>'Admin\ProfileController@postProfile'])->name('admin.postProfile');
//            $router->get('password', ['uses'=>'Admin\ProfileController@getPassword'])->name('admin.getPassword');
//            $router->post('password', ['uses'=>'Admin\ProfileController@postPassword'])->name('admin.postPassword');
//        //    $router->get('dashboard', ['uses'=>'Admin\ProfileController@dashboard'])->name('admin.dashboard');
//            $router->get('/', ['uses'=>'Admin\ProfileController@profile'])->name('admin.profile');
//        });

        $router->group(['middleware' => ['admin_access_permission']], function () use ($router) {
            $listRouter = [
                'Admin\SettingsController' => 'settings',
                'Admin\AdminController' => 'admin',
                'Admin\RoleController' => 'role',
                'Admin\GolonganController' => 'golongan',
                'Admin\UnitKerjaController' => 'unit-kerja',
                'Admin\PendidikanController' => 'pendidikan',
                'Admin\JenjangPerancangController' => 'jenjang-perancang',

                'Admin\KegiatanController' => 'kegiatan',
                'Admin\PermenController' => 'permen',
                'Admin\MsKegiatanController' => 'ms-kegiatan',

            ];
            foreach ($listRouter as $controller => $linkName) {

                $router->get($linkName . '/data', $controller . '@dataTable')->name('admin.' . $linkName . '.dataTable');
                $router->resource($linkName, $controller, ['as' => 'admin']);
            }

         $router->group(['prefix' => 'permen/{parent_id}'], function () use ($router) {
             $router->get('mskegiatan/data', 'Admin\PermenMsKegiatanController@dataTable')->name('admin.mskegiatan.dataTable');
             $router->resource('mskegiatan', 'Admin\PermenMsKegiatanController', ['as' => 'admin']);
             });
            });
            $router->get('report', 'Admin\ReportController@index')->name('admin.report');
            $router->get('/', ['uses' => 'Admin\DashboardController@dashboard'])->name('admin');


           // $router->get('portal', ['uses'=>'Admin\HomeController@portal'])->name('admin.portal');
            $router->get('data-informasi', ['uses' => 'Admin\HomeController@dataInformasi'])->name('admin.dataInformasi');


    });

});


Route::get('truncate', 'Admin\HomeController@truncate');
//Route::get('check-butir-kegiatan', 'Admin\HomeController@checkButirKegiatan');
Route::get('fixing-data', 'Admin\HomeController@fixingData');
