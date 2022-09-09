<?php

use entities\Game;
use entities\Mission;
use entities\Base;

$baseId = $request->data['id'];
$colorId = $request->data['ColorId'];
$config = $db->getRow("SELECT * FROM `BaseList` WHERE `id`='{$baseId}'");


$gameData = $game->getGame();
$round = $game->getRound();

// Base
$base = new Base($config['Address'], $config['Name'], $db);


// Если раунд начат
if($round) {
    // Показываем или скрываем легенду
    if($base->getCurrentView()) {
        $base->removeCurrentView();
    } else {
        // Легенда
        $columnName = $globalTeam[$colorId].'Legend';
        $legend = $game->getLegend($round['MissionId'], $columnName);

        $base->setCurrentView($legend);
    }
} else {
    $base->removeCurrentView();
}