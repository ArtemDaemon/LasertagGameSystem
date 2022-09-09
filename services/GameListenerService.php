<?php
use entities\Mission;
use entities\Point;
use entities\Base;

// Game
$game->init();

// Если раунд не начался прекращаем обработку
if(!$game->isRoundStarted()) {exit;}
// Round
$round = $game->getRound();

// Mission
$mission = new Mission($db);
$mission->init($round['MissionId']);
$missionTime = $mission->getMaxTime();
$missionScore = $mission->getMaxScore();


// Sound Preset
$sound->setup($mission->getSoundConfig());

// События игры
if($round['StartDate']!=NULL && $round['EndDate']==NULL) {
    // Запускаем только после прошествия 2 секунд со старта
    $tPass = time() - strtotime($round['StartDate']);
    if($tPass<2) exit;


    // тут косяк с постоянным воспр звука на 10 сек
    // Временные звуки в зависимости от того сколько идет раунд, идет сравнение с MissionList\TimeToEnd
    $sound->playSoundTime(
        $game->roundTimeLeft($missionTime)
    );


    switch ($mission->pointsType()) {
        case Mission::MISSION_TIME:
            $handler = 'accumulation_of_time';
            break;
        case Mission::MISSION_SHOOTOUT_BY_TURNS:
            $handler = 'shootout_by_turns';
            break;
        case Mission::MISSION_SEQUENTIAL_SEIZURE:
            $handler = 'sequential_seizure';
            break;
        case Mission::MISSION_CAPTURE_THE_FLAG:
            $handler = 'capture_flag';
            break;
        case Mission::MISSION_HOME_DESTROING:
            $handler = 'home_destroing';
            break;
        case Mission::MISSION_BOMB_PLANT:
            $handler = 'bombplant';
            break;
        case Mission::MISSION_SURVIVAL:
            $handler = 'survival';
            break;
    }

    require $_SERVER['DOCUMENT_ROOT'] . '/services/game_listener_handlers/' . $handler . '.php';
}


// Обновляем статусы у точек
$allPoints = $game->getAllPointsInGame();
if($allPoints) {
    foreach($allPoints as $item) {
        $pointInited = new Point($item['Address'], $item['Name'], $db);
        $lastListen = strtotime($item['last_listen']) + DOT_TIMEOUT;
        $now = time();


        if ($now > $lastListen) {
            $pointInited->setStatus(Point::STATUS_ERROR);
        }
    }
}

// Обновляем статусы у баз
$bases = $game->getAllBases();
$readyCount = 0;
foreach ($bases as $item) {
    // ReadyCounter++
    if($item['isReady']==1) {
        $readyCount++;
    }

    $item = new Base($item['Address'], $item['Name'], $db);

    // Timeout
    $lastListen = strtotime($item->getLastListen()) + DOT_TIMEOUT;
    $now = time();
    if ($now > $lastListen) {
        $item->setStatus(Base::STATUS_ERROR);
    }
}

// Базы готовы и раунд не начат
if($readyCount==2 && $round['StartDate']==NULL) {
    $game->getGame();
    $mission = new Mission($db);
    $mission->init($round['MissionId']);

    $game->setReadyAllBases();
    $game->basesReinit($round['MissionId']);

    if($round) {
        $sound->setup($mission->getSoundConfig());
        $sound->playRoundStartSound();
        $sound->playStartBg();

        //$game->rememberSettings($request->data['anyId'], $request->data['id1'], $request->data['id2'], $request->data['id3']);
        $game->roundStart();

        $game->setupPoints(
            $mission->id,
            $round['anyId'] ?: 0,
            $round['id1'] ?: 0,
            $round['id2'] ?: 0,
            $round['id3'] ?: 0
        );
    }
}