<?php
use entities\Mission;
use entities\Point;
use entities\Base;


// События игры
//if($round['StartDate']!=NULL && $round['EndDate']==NULL) {
    // Раунд Завершен
    if ($game->isRoundTimeout($missionTime) || $game->isRoundScoreOut($missionScore)) {
        $finalPoint = $mission->getFinalPoint();
        $finalPoint = $game->getPoint($finalPoint);
        $point = new Point($finalPoint['Address'], $finalPoint['Name'], $db);
        $pointActionTime = $point->lastActionPastTime();


        // Final Point Init
        if(!$pointActionTime) {
            $game->offPoints();
            $setup = $point->getSetup($mission->id);
            $preset = $game->getPresetMain($setup['MainPresetId']);
            $resource = $game->getResource($setup['ResourceTakeId']);
            $point->setup([

                'Type' => Point::TYPE_1,
                'id' => $finalPoint['id'],
                'Armor' => 0,
                'SumDamage' => 1,
                'TimeResetSum' => 1,
                'NeedTime' => 0,
                'TypeWork' => 4,
                'ResourceTakeId' => 0,
                'ResourceIdComlite' => 0,
                'ColorId' => 3,
                'TimeReset' => 10,
                'AnyId' => 1,
                'id1' => 0,
                'id2' => 0,
                'id3' => 0,
            ]);

            $point->setStatus(Point::STATUS_WAITING);
            $sound->playAlarm();
            $point->setActionDate();
        }

        // Если никто не успеет выстрелить за отведенное время
        if($pointActionTime) {
            $duration = $mission->getDurationAlarm();
            if ($pointActionTime >= $duration) {
                $sound->playRoundStopSound();

                // Заканчиваем раунд.
                $sound->stopSound();

                $game->unReadyBases();
                $game->endRound();
                $round = false;
            }
        }
    }
//}