<?php

use entities\Game;
use entities\Mission;
use entities\Base;

/**
 * Обработчик баз
 */
\classes\Logger::getLogger(LOG_NAME)->log('Обработчик баз: ' . json_encode($_REQUEST));


$baseId = $request->data['id'];
$Type = $request->data['Type'];
$config = $db->getRow("SELECT * FROM `BaseList` WHERE `id`='{$baseId}'");

$gameData = $game->getGame();
$round = $game->getRound();

// Base
$base = new Base($config['Address'], $config['Name'], $db);

// Mission
$mission = new Mission($db);
$mission->init($round['MissionId']);
$sound->setup($mission->getSoundConfig());
$teamName = $globalTeam[$base->getBaseColorId($round['MissionId'])];


if($round) {
    // Принятие запроса на готовность
    if($Type==Base::TYPE_PRESS_WAIT) {
        $game->baseReady($baseId);

        $sound->playTeamReady($teamName);
    }

    // Ограниченное возрождение
    if($Type==Base::TYPE_LIMITED_REVIVAL) {
        // 0 - время работы закончилось, база выключилась, 1 - работает
        $base->setStatus($request->data['Status']);
        // 10 количество доступных возрождений
        $base->setNRessurections($request->data['N']);
    }



    // Склад
    if($Type==Base::TYPE_STOCK) {
        $What = $request->data['What'];
        if($base->removeResource($What)) {
            $base->stockReinit($mission->getMission(), $teamName);
        }
    }
}
