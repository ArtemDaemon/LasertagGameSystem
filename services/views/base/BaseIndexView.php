<?php
use entities\Game;
use entities\Base;


$game = new Game($db);
$game->getGame();
$round = $game->getRound();
$points = $game->getAllPoints();



$readyBases = $game->getAllBases();
$readyCount = 0;
if($readyBases) {
    foreach($readyBases as $item) {
        if($item['isReady']==1) {
            $readyCount++;
        }
    }
}

$round = $game->getRound();
$mission = new \entities\Mission($db);
if($round) {
    $mission->init($round['MissionId']);
}

// Base
$config = $db->getRow("SELECT * FROM `BaseList` WHERE `id`='{$request->data['baseId']}'");
$base = new Base($config['Address'], $config['Name'], $db);


// Главная
if($base->isOff() || !$game->isRoundStarted()) {
    $view = 'base/base-index-view';
}

$baseData = $base->getBase();

// База отключена по таймоверу
if($baseData['TimeOver']==1) {
    $view = 'base/base-timeover-view';
}


// Нажмите кнопку
if($base->isPressWait()) {
    $view = 'base/base-ready-view';
}

// Кнопки нажаты, игра
if($readyCount==2 && $baseData['TimeOver']!=1) {
    $missionInfo = $mission->getMission();
    $colorName = $globalTeam[$base->getBaseColorId($round['MissionId'])];

    $view = 'base/base-bytype-view';
}


// Отображение легенды
if($legend = $base->getCurrentView()) {
    $view = 'base/legend/'.$legend;
}

require($_SERVER['DOCUMENT_ROOT'] . '/view/'.$view.'.php');