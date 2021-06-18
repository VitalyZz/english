<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Src\App\Container\Container;
use Src\App\Router\Router;
use Src\App\Storage\SessionStorage;
use Src\Controllers\AuthController;
use Src\App\Database\Connection;
use Src\Controllers\DictionaryController;
use Src\Controllers\TextController;
use Src\Controllers\WordsController;

require_once dirname(__DIR__) . '/config.php';

header('Access-Control-Allow-Origin: ' . ORIGIN);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Accept, Content-Type, x-requested-with, SameSite, Set-Cookie');

require_once dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();
$container = new Container();

$router->add('/login', AuthController::class, 'login');
$router->add('/signup', AuthController::class, 'signup');
$router->add('/check', AuthController::class, 'check');
$router->add('/logout', AuthController::class, 'logout');
$router->add('/checkEmail', AuthController::class, 'checkEmail');

$router->add('/texts/getAll', TextController::class, 'getAll');
$router->add('/texts/getPublic', TextController::class, 'getPublic');
$router->add('/text/getById', TextController::class, 'getById');
$router->add('/text/getByIdWithoutUser', TextController::class, 'getByIdWithoutUser');
$router->add('/text/getByIdPublic', TextController::class, 'getByIdPublic');
$router->add('/text/create', TextController::class, 'create');
$router->add('/text/createAdmin', TextController::class, 'createAdmin');
$router->add('/text/delete', TextController::class, 'delete');
$router->add('/text/update', TextController::class, 'update');
$router->add('/text/checkPublic', TextController::class, 'checkPublic');

$router->add('/dictionary/getDictionaryById', DictionaryController::class, 'getDictionaryById');
$router->add('/dictionary/getAll', DictionaryController::class, 'getAll');
$router->add('/dictionary/delete', DictionaryController::class, 'delete');
$router->add('/dictionary/create', DictionaryController::class, 'create');
$router->add('/dictionary/statistics', DictionaryController::class, 'statistics');

$router->add('/words/getByDictionaryId', WordsController::class, 'getByDictionaryId');
$router->add('/words/getByMeaning', WordsController::class, 'getByMeaning');
$router->add('/words/getByMeaningWithTranslations', WordsController::class, 'getByMeaningWithTranslations');
$router->add('/words/insertTranslation', WordsController::class, 'insertTranslation');
$router->add('/words/getAll', WordsController::class, 'getAll');
$router->add('/words/getStudyWords', WordsController::class, 'getStudyWords');
$router->add('/words/delete', WordsController::class, 'delete');
$router->add('/words/deleteWithoutDictionaryById', WordsController::class, 'deleteWithoutDictionaryById');
$router->add('/words/getAllCountOfWords', WordsController::class, 'getAllCountOfWords');
$router->add('/word/changeMeaning', WordsController::class, 'changeMeaning');
$router->add('/word/deleteWithoutDictionary', WordsController::class, 'deleteWithoutDictionary');
$router->add('/word/update', WordsController::class, 'update');
$router->add('/word/updateByChangeDictionary', WordsController::class, 'updateByChangeDictionary');
$router->add('/word/getIdByWord', WordsController::class, 'getIdByWord');

$container->set(Connection::class, function() {
    return new Connection("mysql:host=" . HOST . ";dbname=" . DBNAME, USERNAME, PASSWORD);
});

$container->set(SessionStorage::class, function () {
    return new SessionStorage();
});

$connection = $container->get(Connection::class);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$container->set(AuthController::class, function(Container $container) {
    return new AuthController(
        $container->get(Connection::class),
        $container->get(SessionStorage::class)
    );
});

$container->set(TextController::class, function(Container $container) {
    return new TextController($container->get(Connection::class));
});

$container->set(DictionaryController::class, function(Container $container) {
    return new DictionaryController($container->get(Connection::class));
});

$container->set(WordsController::class, function(Container $container) {
    return new WordsController($container->get(Connection::class));
});

try {
    $match = $router->match(preg_replace("#\?.*#", null, $_SERVER['REQUEST_URI']));

    $controller = $container->get($match['_controller']);

    $response = call_user_func([$controller, $match['_action']]);

    echo json_encode($response);
} catch (DomainException $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}