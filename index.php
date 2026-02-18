<?php
/**
 * Farol de Luz - Front Controller
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/revista', 'MagazineController@index');
$router->get('/revista/{slug}', 'MagazineController@show');

$router->get('/dialogos', 'DialogoController@index');
$router->get('/dialogos/{slug}', 'DialogoController@show');

$router->get('/rajian', 'RajianController@index');
$router->get('/rajian/{slug}', 'RajianController@show');

$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'BlogController@show');

$router->get('/sobre', 'PageController@about');
$router->get('/sobre/batuira', 'PageController@batuira');

$router->get('/contato', 'ContatoController@index');
$router->post('/contato', 'ContactController@send');

$router->post('/api/newsletter/subscribe', 'NewsletterController@subscribe');
$router->post('/api/newsletter/unsubscribe', 'NewsletterController@unsubscribe');
$router->get('/api/revista/latest', 'MagazineController@latest');

$router->get('/admin', 'Admin/AuthController@login');
$router->post('/admin/login', 'Admin/AuthController@authenticate');
$router->get('/admin/logout', 'Admin/AuthController@logout');

$router->get('/admin/dashboard', 'Admin/DashboardController@index');

$router->get('/admin/revistas', 'Admin/MagazineController@index');
$router->get('/admin/revistas/criar', 'Admin/MagazineController@create');
$router->post('/admin/revistas/criar', 'Admin/MagazineController@store');
$router->get('/admin/revistas/{id}/editar', 'Admin/MagazineController@edit');
$router->post('/admin/revistas/{id}/editar', 'Admin/MagazineController@update');
$router->post('/admin/revistas/{id}/deletar', 'Admin/MagazineController@delete');

$router->get('/admin/dialogos', 'Admin/DialogoController@index');
$router->get('/admin/dialogos/criar', 'Admin/DialogoController@create');
$router->post('/admin/dialogos/criar', 'Admin/DialogoController@store');
$router->get('/admin/dialogos/editar/{id}', 'Admin/DialogoController@edit');
$router->post('/admin/dialogos/editar/{id}', 'Admin/DialogoController@update');
$router->post('/admin/dialogos/excluir/{id}', 'Admin/DialogoController@delete');

$router->get('/admin/rajian', 'Admin/RajianController@index');
$router->get('/admin/rajian/criar', 'Admin/RajianController@create');
$router->post('/admin/rajian/criar', 'Admin/RajianController@store');
$router->get('/admin/rajian/{id}/editar', 'Admin/RajianController@edit');
$router->post('/admin/rajian/{id}/editar', 'Admin/RajianController@update');
$router->post('/admin/rajian/{id}/deletar', 'Admin/RajianController@delete');

$router->get('/admin/blog', 'Admin/BlogController@index');
$router->get('/admin/blog/criar', 'Admin/BlogController@create');
$router->post('/admin/blog/criar', 'Admin/BlogController@store');
$router->get('/admin/blog/{id}/editar', 'Admin/BlogController@edit');
$router->post('/admin/blog/{id}/editar', 'Admin/BlogController@update');
$router->post('/admin/blog/{id}/deletar', 'Admin/BlogController@delete');

$router->get('/admin/newsletter', 'Admin/NewsletterController@index');
$router->post('/admin/newsletter/deletar', 'Admin/NewsletterController@delete');
$router->get('/admin/newsletter/export', 'Admin/NewsletterController@export');

$router->get('/admin/taxonomias', 'Admin/TaxonomyController@index');
$router->get('/admin/taxonomias/criar', 'Admin/TaxonomyController@create');
$router->post('/admin/taxonomias/criar', 'Admin/TaxonomyController@store');
$router->get('/admin/taxonomias/{id}/editar', 'Admin/TaxonomyController@edit');
$router->post('/admin/taxonomias/{id}/editar', 'Admin/TaxonomyController@update');
$router->post('/admin/taxonomias/{id}/deletar', 'Admin/TaxonomyController@delete');

$router->get('/admin/configuracoes', 'Admin/SettingsController@index');
$router->post('/admin/configuracoes', 'Admin/SettingsController@update');

$router->post('/admin/test-email', 'Admin/TestEmailController@send');

$router->post('/admin/upload-image', 'Admin/UploadController@uploadImage');

$router->get('/sitemap.xml', 'SitemapController@index');
$router->get('/robots.txt', 'RobotsController@index');

$url = $_GET['url'] ?? '/';

error_log("URL recebida: " . $url);
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);

$router->dispatch($url);
