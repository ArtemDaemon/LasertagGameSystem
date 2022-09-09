<?php
use entities\Game;
use entities\Mission;
use entities\Base;


//\classes\Logger::getLogger('log_listen')->log('Слушатель базы: ' . json_encode($_REQUEST));
/**
 *  Отправляем запрос к точке
 */

//$game->getGame();
$game->init();
// Если раунд не начался прекращаем обработку
if(!$game->isRoundStarted()) {exit;}

$baseId = $request->data['id'];
$Type = $request->data['Type'];

// Round
$round = $game->getRound();

// Mission
$mission = new Mission($db);
$mission->init($round['MissionId']);
$missionTime = $mission->getMaxTime();
$missionScore = $mission->getMaxScore();
$missionInfo = $mission->getMission();



$config = $db->getRow("SELECT * FROM `BaseList` WHERE `id`='{$baseId}'");
$base = new Base($config['Address'], $config['Name'], $db);

$currBaseColorName = $globalTeam[$base->getBaseColorId($round['MissionId'])];
$timePass = time() - strtotime($round['StartDate']);

// Обновляем данные баз
if($Type==100 || $Type==0) {
    $base->setStatus(Base::STATUS_OFF);
} else {
    $base->setStatus(Base::STATUS_ON);
    $base->setType($Type);
}

$base->lastListenUpdate();

if($round['StartDate']) {
    if(!$base->isNotTimeOver()) exit;


    // Check Timeover Timeout
    $bases = $game->getAllBases();
    foreach ($bases as $item) {
        $item = new Base($item['Address'], $item['Name'], $db);
        $colorName = $globalTeam[$item->getBaseColorId($round['MissionId'])];

        $lastListen = strtotime($item->getLastListen()) + DOT_TIMEOUT;
        $now = time();
        if ($now > $lastListen) {
            $item->setStatus(Base::STATUS_OFF);
        }

        $timeWork = (int)$missionInfo['TimeWorkBase' . $colorName];

        // Бесконечное время работы базы
        if($timeWork==Base::INFINITY_TIME_WORK) continue;


        // Если время работы окончено
        if (($timePass >= $timeWork) && $item->isNotTimeOver()) {
            $item->timeOver();
            $item->stockReinit($missionInfo, $globalTeam[$item->getBaseColorId($round['MissionId'])]);
        }
    }




    // Отправка запроса на возрождение, для базы режима Возрождение по времени
    if($base->getType() == Base::TYPE_REVIVAL_BY_TIME) {
        $lastRessurectionTimePass = $base->getLastRessurectionTimePass();

        echo $lastRessurectionTimePass;
        $ressurection = [
            'colorName' => $currBaseColorName,
            'startRebase' => $missionInfo['StartReBase'.$currBaseColorName],
            'coef' => $missionInfo['TimePushBase'.$currBaseColorName],
            'firstInterval' => $missionInfo['IntervalReBase'.$currBaseColorName],
            'timework' => $missionInfo['TimeWorkBase'.$currBaseColorName],
            'gandicap' => $missionInfo['Gandicap'.$currBaseColorName]
        ];

        $interval = $base->getRessurectionInterval();


        // Воскрешаем первый раз
        if($interval==null && $timePass >= $ressurection['startRebase']) {
            echo 'Воскрешаем первый раз <br />';
            $base->sendRessurection($missionInfo, $currBaseColorName);

            if($ressurection['gandicap']) {
                $ressurectionInterval = $base->ressurectIntervalCalculateGandicap($ressurection['firstInterval'], $ressurection['colorName'], $round['ScoreRed'], $round['ScoreBlue']);
            } else {
                $ressurectionInterval = $base->ressurectIntervalCalculate($ressurection['firstInterval'], $ressurection['coef']);
            }

            $base->setRessurectionInterval($ressurectionInterval);
        }


        // Если воскрешение уже происходило
        if($lastRessurectionTimePass) {
            // Воскрешаем по интервалам [Если с последнего воскрешения прошло времени больше чем длина интервала]
            if ($lastRessurectionTimePass >= $interval) {
                echo 'Воскрешаем по интервалу '.$interval.' <br />';
                $base->sendRessurection($missionInfo, $currBaseColorName);
            }
        }


        // Пересчитываем интервал, если подошло время
        $lastInterValUpdateTime = $base->getLastRessurectionIntervalTimePass();
        if ($interval && $lastInterValUpdateTime >= $ressurection['startRebase']) {

            if($ressurection['gandicap']) {
                $ressurectionInterval = $base->ressurectIntervalCalculateGandicap($interval, $ressurection['colorName'], $round['ScoreRed'], $round['ScoreBlue']);
            } else {
                $ressurectionInterval = $base->ressurectIntervalCalculate($interval, $ressurection['coef']);
            }

            echo 'Пересчет: '.$ressurectionInterval.' <br />';
            $base->setRessurectionInterval($ressurectionInterval);
            $base->lastRessurectionIntervalUpdate();
        }
    }
}