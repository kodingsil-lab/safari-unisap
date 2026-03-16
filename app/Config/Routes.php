<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setAutoRoute(false);

$routes->get('/', 'HomeController::index');
$routes->get('formulir', 'FormPublicController::index');
$routes->get('formulir/(:segment)', 'FormPublicController::show/$1');
$routes->get('formulir/(:segment)/isi', 'FormPublicController::fill/$1');
$routes->post('formulir/(:segment)/submit', 'FormPublicController::submit/$1');
$routes->get('pengajuan/sukses/(:segment)', 'Portal\\SubmissionController::success/$1');
$routes->get('bukti/(:segment)', 'Portal\\StatusController::proof/$1');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->match(['get', 'post'], 'login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout', ['filter' => 'adminAuth']);

    $routes->group('', ['filter' => 'adminAuth'], static function ($routes) {
        $routes->get('/', 'DashboardController::index');
        $routes->get('dashboard', 'DashboardController::index');
        $routes->get('pengajuan', 'SubmissionController::index');
        $routes->get('pengajuan/(:num)', 'SubmissionController::show/$1');
        $routes->get('pengajuan/file/(:num)', 'SubmissionController::downloadFile/$1');
        $routes->post('pengajuan/(:num)/delete', 'SubmissionController::delete/$1');
        $routes->post('pengajuan/bulk-delete', 'SubmissionController::bulkDelete');
        $routes->get('form-categories', 'FormCategoryController::index');
        $routes->match(['get', 'post'], 'form-categories/create', 'FormCategoryController::create');
        $routes->match(['get', 'post'], 'form-categories/edit/(:num)', 'FormCategoryController::edit/$1');
        $routes->post('form-categories/toggle/(:num)', 'FormCategoryController::toggle/$1');
        $routes->post('form-categories/delete/(:num)', 'FormCategoryController::delete/$1');
        $routes->get('forms', 'FormTypeController::index');
        $routes->match(['get', 'post'], 'forms/create', 'FormTypeController::create');
        $routes->match(['get', 'post'], 'forms/edit/(:num)', 'FormTypeController::edit/$1');
        $routes->post('forms/toggle/(:num)', 'FormTypeController::toggle/$1');
        $routes->post('forms/delete/(:num)', 'FormTypeController::delete/$1');
        $routes->get('forms/(:num)/fields', 'FormFieldController::index/$1');
        $routes->match(['get', 'post'], 'forms/(:num)/fields/create', 'FormFieldController::create/$1');
        $routes->match(['get', 'post'], 'forms/(:num)/fields/edit/(:num)', 'FormFieldController::edit/$1/$2');
        $routes->post('forms/(:num)/fields/delete/(:num)', 'FormFieldController::delete/$1/$2');
        $routes->post('forms/(:num)/fields/reorder', 'FormFieldController::reorder/$1');
        $routes->get('export', 'ExportController::index');
        $routes->get('export/excel', 'ExportController::excel');
        $routes->get('export/pdf', 'ExportController::pdf');
        $routes->get('export/pengajuan/(:num)/pdf', 'ExportController::submissionPdf/$1');
        $routes->get('laporan', 'ReportController::index');
        $routes->match(['get', 'post'], 'pengaturan', 'SettingController::index');
        $routes->get('admin-users', 'AdminUserController::index');
        $routes->match(['get', 'post'], 'admin-users/create', 'AdminUserController::create');
        $routes->match(['get', 'post'], 'admin-users/edit/(:num)', 'AdminUserController::edit/$1');
        $routes->post('admin-users/toggle/(:num)', 'AdminUserController::toggle/$1');
    });
});

$routes->get('health', static fn () => service('response')->setJSON(['status' => 'ok']));
