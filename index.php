<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


// Если включено, то GET запросы не отправляются
define('DEBUG_GLOBAL', false);
include('AutoLoader.php');



// Start
use classes\Logger;
use classes\Config;
use classes\SafeMySQL;
use classes\Request;
use entities\Sound;
use entities\Game;

// Настройка хранения логов
Logger::$PATH = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
define('LOG_NAME', date("Y-m-d"));

$config = new Config([
    'db' => [
        'host' => 'localhost',
        'user' => 'phpmyadmin',
        'pass' => 'malina2018',
        'name' => 'dbname',
        'charset' => 'utf8',
    ]
]);


$db = new SafeMySQL([
    'host' => "{$config->db['host']}",
    'user' => "{$config->db['user']}",
    'pass' => "{$config->db['pass']}",
    'db' => "{$config->db['name']}",
    'charset' => "{$config->db['charset']}"
]);


define('DS', DIRECTORY_SEPARATOR);
// ничья
define('DRAW_GAME', 0);

define('RED_TEAM', 1);
define('BLUE_TEAM', 2);
// Понадобилось для определения имени таблицы RedFirstTake
$globalTeam = [
    RED_TEAM => 'Red',
    BLUE_TEAM => 'Blue',
];


define('DOT_TIMEOUT', 10);
define('SOUND_SERVER', 'http://192.168.0.108'); // Без слеша в конце


$routing = [
    // [Вид] - Страница оператора [Главная]
    '' => 'services'.DS.'views'.DS.'operator'.DS.'IndexView',
    // [Вид] - Страница оператора [Ожидание готовности игроков]
    'missionStartView' => 'services'.DS.'views'.DS.'operator'.DS.'BaseReadyWaitView',
    'roundControlView' => 'services'.DS.'views'.DS.'operator'.DS.'RoundControlView',
    'roundEndView' => 'services'.DS.'views'.DS.'operator'.DS.'RoundEndView',

    'gameCheck' => 'services'.DS.'views'.DS.'operator'.DS.'GameCheck',

    // [Вид:База] - Экран базы
    'base-indexView' => 'services'.DS.'views'.DS.'base'.DS.'BaseIndexView',
    // Скрипт:база - показать легенду
    'baseShowLegend' => 'services'.DS.'BaseShowLegendService',

    // [Скрипт] - Инициализация миссии и раунда
    'missionInit' => 'services'.DS.'MissionInitService',

    'forceRoundStart' => 'services'.DS.'ForceRoundStartService',
    'roundEnd' => 'services'.DS.'RoundEndService',
    'cancelMission' => 'services'.DS.'CancelMissionService',

    // [Обработка GET запросов от точки] - Прием запроса от точки
    'PointSendEvent' => 'services'.DS.'PointSendEventService',

    // [Обработка GET запросов от базы] - Прием запроса от базы
    'BaseSendEvent' => 'services'.DS.'BaseSendEventService',

    // [Слушатель] - Статус игры
    'gameListener' => 'services'.DS.'GameListenerService',

    // [Слушатель] - Статус точки
    'PointStatusListener' => 'services'.DS.'PointStatusListenerService',
    // [Слушатель] - Статус базы
    'BaseStatusListener' => 'services'.DS.'BaseStatusListenerService',
];

// Выбор текущей игры
// http://phplasertag.loc/?command=gameCheck&id=1

// Главная страница оператора
// http://phplasertag.loc/

// Ожидание готовности игроков
// http://phplasertag.loc/?command=missionStartView&view=base-ready-wait&missionId=21


// Обработчик статуса игры
// http://phplasertag.loc/?command=gameListener


// Прием запроса от точки [Время]
// http://phplasertag.loc/?command=PointSendEvent&Type=1&id=11&Status=1&FirstTake=120&LastTake=120

// Прием запроса от точки [Уничтожение домов]
// http://phplasertag.loc/?command=PointSendEvent&Type=4&id=21&Status=1&TakeId=120&ColorId=1

// Прием запроса от точки [Выживание]
// http://phplasertag.loc/?command=PointSendEvent&Type=5&id=18&Status=1&TakeId=120&ColorId=1

// Прием запроса от точки [Установка бомбы]
// http://phplasertag.loc/?command=PointSendEvent&Type=6&id=13&Status=2&TagerId=120&ColorId=1




// Прием запроса от точки [Захват флага] - берем флаг (красные)
// http://phplasertag.loc/?command=PointSendEvent&Type=2&id=11&Status=0&PlayerId=130&ColorId=1

// Прием запроса от точки [Захват флага] - захват точки (красные)
// http://phplasertag.loc/?command=PointSendEvent&Type=1&id=13&Status=1&FirstTake=130&LastTake=130

// Прием запроса от точки [Захват флага] - ставим флаг (красные)
// http://phplasertag.loc/?command=PointSendEvent&Type=3&id=12&Status=0&PlayerId=130&ColorId=1

// Обработчик статуса точки
// http://phplasertag.loc/?command=PointStatusListener&Type=1&id=11&Status=1&Time=10

// [База] Экран базы
// http://phplasertag.loc/?command=base-indexView&baseId=1

// [Показать/Скрыть легенду на базе] - запрашиваием этот адрес, и на экране базы появиться легенда
// http://phplasertag.loc/?command=baseShowLegend&id=1&ColorId=1

// Обработчик статуса базы
// http://phplasertag.loc/?command=BaseStatusListener
// Калькуляция воскрешения
// http://phplasertag.loc/?command=BaseStatusListener&id=1&Type=3

// Обработчик запросов баз [готовность команд]
// http://phplasertag.loc/?command=BaseSendEvent&Type=1&id=1

// Обработчик запросов баз [отправка количества возрождений]
// http://phplasertag.loc/?command=BaseSendEvent&Type=4&id=2&Status=1&N=9

// Обработчик запросов баз [трата ресурса на складе]
// http://phplasertag.loc/?command=BaseSendEvent&Type=6&id=1&What=1


$command = (isset($_GET['command'])) ? $_GET['command'] : '';
unset($_GET['command']);
$request = new Request($_GET);

$view = 'index';
if(isset($request->data['view'])) {
    $view = $request->data['view'];
}

$game = new Game($db);

// Sounds
$sound = new Sound($db);
$sound->setServer(SOUND_SERVER);

/**
 *  Request Handler Init
 */
require ($routing[$command] . '.php');