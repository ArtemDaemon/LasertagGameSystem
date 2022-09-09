<?php

namespace classes;

use entities\BaseResources;
use entities\Base;

class BaseHelper
{
    public static function activeBase($teamName)
    {
        $bases = [
            'Blue' => 'Синие',
            'Red' => 'Красные',
        ];
        return $bases[$teamName];
    }

    public static function statusLabel($id)
    {
        $statuses = [
            Base::STATUS_OFF => '<span class="b-text_red">Выключена</span>',
            Base::STATUS_ERROR => '<span class="b-text_red">Не в сети</span>',
            Base::STATUS_ON => '<span class="b-text_green">В сети</span>'
        ];
        return $statuses[$id];
    }

    public static function typeWork($type)
    {
        $types = [
            Base::TYPE_OFF => '-',
            Base::TYPE_PRESS_WAIT => 'Ожидание нажатия',
            Base::TYPE_ENDLESS_REVIVAL => 'Бесконечное возрождение',
            Base::TYPE_REVIVAL_BY_TIME => 'Возрождение по времени',
            Base::TYPE_LIMITED_REVIVAL => 'Ограниченное возрождение',
            Base::TYPE_HEALING => 'Режим лечения',
            Base::TYPE_STOCK => 'Склад'
        ];

        return $types[$type];
    }

    public static function getAdditional($game, $mission, $base, $type)
    {
        $baseData = $base->getBase();
        $missionData = $mission->getMission();
        $colorId = $base->getBaseColorId($mission->id);
        $round = $game->getRound();


        $result = '';
        if($type==Base::TYPE_LIMITED_REVIVAL) {
            if($baseData['nRessurections']==NULL) {
                $ress = ($colorId == Base::BASE_RED) ? $mission->getStartReBaseRed() : $mission->getStartReBaseBlue();
            } else {
                $ressurestions = ($colorId == Base::BASE_RED) ? $mission->getStartReBaseRed() : $mission->getStartReBaseBlue();
                $ress = $baseData['nRessurections'] . '/' . $ressurestions;
            }


            $interval = ($colorId == Base::BASE_RED) ? $mission->getIntervalReBaseRed() : $mission->getIntervalReBaseBlue();

            $timePush = ($colorId == Base::BASE_RED) ? $mission->getBaseTimePushRed() : $mission->getBaseTimePushBlue();

            $result .= '<div>Возрождений: '.$ress.'</div>';
            $result .= '<div>'.self::intervalReBase($interval).'</div>';
            $result .= '<div>Время удержания кнопки: '. $timePush .' с</div>';
        }




        if($type==Base::TYPE_ENDLESS_REVIVAL) {
            $timePush = ($colorId == Base::BASE_RED) ? $mission->getBaseTimePushRed() : $mission->getBaseTimePushBlue();
            $result .= '<div>Время удержания кнопки: '. $timePush .' с</div>';
        }


        if($type==Base::TYPE_HEALING) {
            $timePush = ($colorId == Base::BASE_RED) ? $mission->getBaseTimePushRed() : $mission->getBaseTimePushBlue();
            $result .= '<div>Время удержания кнопки: '. $timePush .' с</div>';
        }




        if($type==Base::TYPE_REVIVAL_BY_TIME) {
            $allBases = $game->getAllBases();
            $redBaseId = $missionData['RedBaseId'];
            $blueBaseId = $missionData['BlueBaseId'];


            $rebaseInt = ($colorId == Base::BASE_RED) ? $missionData['StartReBaseRed'] : $missionData['StartReBaseBlue'];
            $result = '<div>Интервал: '.$rebaseInt.' с</div>';

            if($allBases) {
                foreach ($allBases as $item) {
                    if ($item['id'] == $redBaseId) {
                        $redInterval = ($item['ressurectionInterval']) ? $item['ressurectionInterval'] : $missionData['IntervalReBaseRed'];
                    }
                    if ($item['id'] == $blueBaseId) {
                        $blueInterval = ($item['ressurectionInterval']) ? $item['ressurectionInterval'] : $missionData['IntervalReBaseBlue'];
                    }
                }

                if($redInterval!=null && $blueInterval!=null) {
                    $data = ($baseData['id'] == $redBaseId) ? 'Красных ' . $redInterval : 'Синих ' . $blueInterval;

                    $result .= '<div>Частота у ' . $data . ' с</div>';
                }
            }
        }



        if($type==Base::TYPE_STOCK) {
            $timePush = ($colorId == Base::BASE_RED) ? $mission->getBaseTimePushRed() : $mission->getBaseTimePushBlue();

            if($baseData['resources']) {
                $resources = $base->getResources();

                $list = '';
                if ($resources->getAchivments()) {
                    foreach ($resources->getAchivments() as $achivment) {
                        $list .= '<option value="' . $achivment['id'] . '">' . $achivment['name'] . '</option>';
                    }
                }
                $result .= '<div>Реанимация: '. $resources->getRessurections() .'</div>';
                $result .= '<div>Патронов: '. $resources->getAmmo() .'</div>';
                $result .= '<div>Время удержания кнопки: '. $timePush .' с</div>';
                $result .= '<div>Список доступных ресурсов</div>
                            <div class="b-select_multiple">
                                <select size="10" multiple="multiple" disabled>'.$list.'</select>
                            </div>';
            } else {
                $colorName = ($colorId==Base::BASE_RED) ? 'Red' : 'Blue';

                // Default Resources
                $result .= '<div>Реанимация: '. $mission->startReBase($colorName) .'</div>';
                $result .= '<div>Патронов: '. $mission->startReWeapon($colorName) .'</div>';
                $result .= '<div>Время удержания кнопки: '. $timePush .' с</div>';
                $result .= '<div>Список доступных ресурсов</div>
                            <div class="b-select_multiple">
                                <select size="10" multiple="multiple" disabled></select>
                            </div>';
            }
        }

        return $result;
    }


    public static function getAdditionalDiz($game, $mission, $base, $type)
    {
        $baseData = $base->getBase();
        $missionData = $mission->getMission();
        $colorId = $base->getBaseColorId($mission->id);
        $round = $game->getRound();


        $result = '';
        if($type==Base::TYPE_LIMITED_REVIVAL) {
            if($baseData['nRessurections']==NULL) {
                $ress = ($colorId == Base::BASE_RED) ? $mission->getStartReBaseRed() : $mission->getStartReBaseBlue();
            } else {
                $ressurestions = ($colorId == Base::BASE_RED) ? $mission->getStartReBaseRed() : $mission->getStartReBaseBlue();
                $ress = $baseData['nRessurections'] . '/' . $ressurestions;
            }
            $result=$ress;
        }

        return $result;
    }


    public static function intervalReBase($time)
    {
        if($time) {
            return 'Период восполнения ' . $time . ' с';
        }
        return false;
    }

    public static function baseName($id)
    {
        $bases = [
            Base::BASE_BLUE => 'База синих',
            Base::BASE_RED => 'База красных',
        ];
        return $bases[$id];
    }

    public static function calculateToTimeOver($startDate, $timeWork)
    {
        $timePass = time() - $startDate;
        if($timeWork==\entities\Base::INFINITY_TIME_WORK) {
            return 'Работает бесконечно';
        }
        if($timePass>=$timeWork) {
            return 'Отключена. Время работы: '.date("i:s", $timeWork);
        } else {
            $timeToOffline = $timeWork - $timePass;
            return 'Работает. До отключения: '.date("i:s", $timeToOffline);
        }
    }
}