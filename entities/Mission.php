<?php

namespace entities;


class Mission extends Object
{
    public $id;

    private $_db;
    private $_inited = false;
    private $_mission;


    const MISSION_TIME = 1;
    const MISSION_SHOOTOUT_BY_TURNS = 2;
    const MISSION_SEQUENTIAL_SEIZURE = 3;
    const MISSION_CAPTURE_THE_FLAG = 4;
    const MISSION_HOME_DESTROING = 5;
    const MISSION_SURVIVAL = 6;
    const MISSION_BOMB_PLANT = 7;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function init($missionId)
    {
        $this->id = $missionId;
        $this->_inited = true;
    }

    public function getPointSetup($id)
    {
        if($this->isInited()) {
            return $this->_db->getRow("SELECT * FROM `MissionPointSetup` WHERE `MissionId`='{$this->id}' AND `PointId`='{$id}'");
        }
    }
    public function getNeedTime($id)
    {
        $point = $this->getPointSetup($id);
        return $point['NeedTime'] ?: false;
    }
    public function getPriority($id)
    {
        $point = $this->getPointSetup($id);
        return $point['Order'] ?: false;
    }


    public function getHandler()
    {
        $mission = $this->getMission();
        return $mission['handler'];
    }

    public function getName()
    {
        $mission = $this->getMission();
        return $mission['Name'];
    }

    public function getSoundPresetId()
    {
        $mission = $this->getMission();
        return $mission['SoundPointId'];
    }

    public function startReBase($colorName)
    {
        $mission = $this->getMission();
        return $mission['StartReBase'.$colorName];
    }
    public function getStartReBaseRed()
    {
        $mission = $this->getMission();
        return $mission['StartReBaseRed'];
    }
    public function getStartReBaseBlue()
    {
        $mission = $this->getMission();
        return $mission['StartReBaseBlue'];
    }
    public function getIntervalReBaseRed()
    {
        $mission = $this->getMission();
        return $mission['IntervalReBaseRed'];
    }
    public function getIntervalReBaseBlue()
    {
        $mission = $this->getMission();
        return $mission['IntervalReBaseBlue'];
    }
    public function getMaxTime()
    {
        $mission = $this->getMission();
        return $mission['MaxTime'];
    }
    public function getMaxScore()
    {
        $mission = $this->getMission();
        return $mission['MaxScore'];
    }
    public function getScore()
    {
        $mission = $this->getMission();
        return $mission['Score'];
    }
    public function isTowards()
    {
        $mission = $this->getMission();
        return $mission['Towards'] == 1;
    }
    public function getWhoCanId()
    {
        $mission = $this->getMission();
        return $mission['WhoCanId'];
    }
    public function startReWeapon($colorName)
    {
        $mission = $this->getMission();
        return $mission['StartReWeaponBase'.$colorName];
    }
    public function getResourceId()
    {
        $mission = $this->getMission();
        return $mission['ResourceId'];
    }
    public function timeResetTogether()
    {
        $mission = $this->getMission();
        return $mission['TimeResetTogether'];
    }
    public function getRedBase()
    {
        $mission = $this->getMission();
        return $mission['RedBaseId'];
    }
    public function getBlueBase()
    {
        $mission = $this->getMission();
        return $mission['BlueBaseId'];
    }

    public function getBaseTimePushRed()
    {
        $mission = $this->getMission();
        return $mission['TimePushBaseRed'];
    }

    public function getBaseTimePushBlue()
    {
        $mission = $this->getMission();
        return $mission['TimePushBaseBlue'];
    }

    public function getDurationAlarm()
    {
        $mission = $this->getMission();
        return $mission['DurationAlarm'];
    }
    public function getFinalPoint()
    {
        $mission = $this->getMission();
        return $mission['FinalPoint'];
    }

    public function getBombPeriod()
    {
        $mission = $this->getMission();
        return $mission['PeriodBomb'];
    }
    public function getBombTimeForPlant()
    {
        $mission = $this->getMission();
        return $mission['TimerForSetup'];
    }
    public function getTimerBomb()
    {
        $mission = $this->getMission();
        return $mission['TimerBomb'];
    }
    public function pointsType()
    {
        $mission = $this->getMission();
        return $mission['TypePoint'];
    }
    public function getPeriodWeapon()
    {
        $mission = $this->getMission();
        return $mission['PeriodWeapon'];
    }

    public function getSoundConfig()
    {
        $mission = $this->getMission();
        return [
            'point' => $mission['SoundPointId'],
            'base' => $mission['IdSoundBase'],
            'round' => $mission['IdSoundRound'],
            'surv' => $mission['SoundPointId'],
        ];
    }

    private function isInited()
    {
        return $this->_inited;
    }
    public function getMission()
    {
        if(!$this->_mission) {
            $this->_mission = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$this->id}'");
        }
        return $this->_mission;
    }
}