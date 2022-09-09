<?php

use entities\Game;
use entities\Mission;
use entities\Point;
use entities\Player;


/**
 * Обработчик точек
 */
\classes\Logger::getLogger(LOG_NAME)->log('Обработчик точек: ' . json_encode($_REQUEST));


$Status = $request->data['Status'];
$Type = $request->data['Type'];
$pointId = $request->data['id'];
$config = $db->getRow("SELECT * FROM `PointList` WHERE `id`='{$pointId}'");

// Game
$gameData = $game->getGame();
$round = $game->getRound();
// Если раунд не начался прекращаем обработку
if(!$game->isRoundStarted()) {exit;}


// Mission
$mission = new Mission($db);
$mission->init($round['MissionId']);
$missionSetup = $mission->getMission();
$pointSetup = $mission->getPointSetup($pointId);

// Sound Preset
$sound->setup($mission->getSoundConfig());

// Point
$point = new Point($config['Address'], $config['Name'], $db);

// Обрабатываем в зависимости от типа точки
require $_SERVER['DOCUMENT_ROOT'] . "/services/point_event_handlers/type_".$Type.'.php';


/**
 * Добавляем ресурс к базе, если у команды есть база - склад
 */
$base = $game->getBaseById($missionSetup[$globalTeam[$TeamColorId].'BaseId']);
$base = new \entities\Base($base['Address'], $base['Name'], $db);
if($base->isStock()) {
    if($missionSetup['TypePoint'] == Mission::MISSION_TIME) {
        $resource = $game->getResource($pointSetup['ResourceIdComlite']);
    }
    if($missionSetup['TypePoint'] == Mission::MISSION_SHOOTOUT_BY_TURNS) {
        $resource = $game->getResource($pointSetup['ResourceTakeId']);
    }
    if($missionSetup['TypePoint'] == Mission::MISSION_SEQUENTIAL_SEIZURE) {
        $resource = $game->getResource($pointSetup['ResourceTakeId']);
    }
    if($missionSetup['TypePoint'] == Mission::MISSION_CAPTURE_THE_FLAG) {
        if($Type == Point::TYPE_1) {
            $resource = $game->getResource($pointSetup['ResourceTakeId']);
        }
        if($Type == Point::TYPE_3) {
            $ColorId = $request->data['ColorId'];
            $resource = $game->getResource($pointSetup[$globalTeam[$ColorId].'ResourceId']);
        }
    }
    if($missionSetup['TypePoint'] == Mission::MISSION_HOME_DESTROING) {
        $resource = $game->getResource($pointSetup['ResourceTakeId']);
    }

    if(isset($resource) && $resource['TypeId']==2) {
        $base->addResource($resource);

        $sound->playBaseResourceTake($resource['id'], $missionSetup['IdSoundBase'], $base->getId());

        $base->stockReinit($missionSetup, $globalTeam[$TeamColorId]);
    }
}