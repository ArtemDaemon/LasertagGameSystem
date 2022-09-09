<?php
use entities\Point;
use entities\Player;

$Type = $request->data['Type'];
$Status = $request->data['Status'];
$TagerId = $request->data['TagerId'];
$TeamColorId = $request->data['ColorId'];


// Player First
if($Status != Point::EVENT_BOMB_PRODUCED) {
    $player = new Player($db);
    $player->init($gameData['id'], $TeamColorId, $TagerId);
}

/*// Добавляем игроку очки за захват точки
$score = $pointSetup['ScorePlayer'];

$player->addScore($score);

// Добавляем к количеству захватов точек для игрока
$player->takePoint();
$point->setTakeTager($TakeId);*/


$TeamColorName = $globalTeam[$TeamColorId];



// Bomb Ready
if($Status == Point::EVENT_BOMB_PRODUCED) {
    $sound->playMakeBomb($TeamColorName);
}

// Bomb Take
if($Status == Point::EVENT_BOMB_TAKED) {
    if(!$player->hasBomb()) {
        $sound->playTakeBomb($TeamColorName);

        $player->takeBomb($pointId);
        $game->pointCanInit($pointId, 1);
        $game->reinitPointsBomb($mission->id, $player);
    }
}

// Bomb Planted
if($Status == Point::EVENT_BOMB_PLANTED) {
    $sound->playSetupBomb($TeamColorName);
    $sound->playBombBeep();
    $player->removeBomb();


    // Reinit Points
    $game->pointCanInit($pointId, 0);
    $game->reinitPointsBomb($mission->id);
}


// Bomb Defused
if($Status == Point::EVENT_BOMB_DEFUSED) {
    $sound->playDefuseBomb($TeamColorName);

    $player->addScore($pointSetup['ScorePlayerDefuse']);

    // Reinit Points
    $game->pointCanInit($pointId, 1);
    $game->reinitPointsBomb($mission->id);
}

// Bomb Destroyed
if($Status == Point::EVENT_BOMB_DESTROYED) {
    $sound->playBombExplosion();

    // Добавляем игроку очки
    $player->addScore($pointSetup['ScorePlayer']);

    // Добавляем к количеству захватов точек для игрока
    $player->takePoint();

    // Добавляем команде очки за захват точки
    $game->teamAddScore($TeamColorName, $pointSetup['ScoreExpl']);

    $game->pointCanInit($pointId, 1);
}