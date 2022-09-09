<?php
namespace entities;

class Point extends Dot {
    // Time ???
    const STATUS_NEUTRAL = 0;
    const STATUS_RED = 1;
    const STATUS_BLUE = 2;


    /* Common statuses */
    const STATUS_OFF = NULL;
    const STATUS_NOT_PLAY = 9;
    const STATUS_ERROR = 10;


    /* Survival */
    const STATUS_WAITING = 1;
    const STATUS_GIVES_AMMUNATION = 2;
    const STATUS_DO_RAD = 3;



    /* Bomb */
    const STATUS_BOMB_NO_PLANTED = 0;
    const STATUS_BOMB_READY = 1;
    const STATUS_BOMB_PRODUCTION = 2;
    const STATUS_BOMB_PLANTED = 3;
    const STATUS_BOMB_EXPLODED = 5;

    const EVENT_BOMB_PRODUCED = 1;
    const EVENT_BOMB_TAKED = 2;
    const EVENT_BOMB_PLANTED = 3;
    const EVENT_BOMB_DEFUSED = 4;
    const EVENT_BOMB_DESTROYED = 5;

    const TYPE_BOMB_RED_TAKE = 1;
    const TYPE_BOMB_BLUE_TAKE = 2;
    const TYPE_BOMB_RED_AIM = 3;
    const TYPE_BOMB_BLUE_AIM = 4;



    /* Flag */
    const TYPE_CAPTURE_FLAG_STATUS_ON = 1;

    const TYPE_CAPTURE_FLAG_RED_BASE = 1;
    const TYPE_CAPTURE_FLAG_RED_AIM = 2;
    const TYPE_CAPTURE_FLAG_BLUE_BASE = 3;
    const TYPE_CAPTURE_FLAG_BLUE_AIM = 4;
    const TYPE_CAPTURE_FLAG_POINT = 5;

    const TYPE_CAPTURE_FLAG_STATUS_FLAG_ON_POINT = 1;
    const TYPE_CAPTURE_FLAG_STATUS_NO_FLAG_ON_POINT = 0;





    /* Global Point Types */
    const TYPE_1 = 1;
    const TYPE_2 = 2;
    const TYPE_3 = 3;
    const TYPE_4 = 4;
    const TYPE_5 = 5;
    const TYPE_6 = 6;


    public function setup($config)
    {
        if(DEBUG_GLOBAL === true) {
            //echo 'Точка: "' . $this->name . '" инициализирована.<br />';
            return;
        }

        return $this->curlContents($this->address . '/setup.php', 'GET', $config);
    }

    public function takePoint($pointId, $tagerId, $teamPointTake, $Status)
    {
        $this->setTeamScore($pointId, $teamPointTake);
        $this->setTakeTager($tagerId);
        //$this->setStatus($Status);
    }
    public function getSetup($missionId)
    {
        $point = $this->getMe();
        return $this->_db->getRow("SELECT * FROM `MissionPointSetup` WHERE `MissionId`='{$missionId}' AND `PointId`='{$point['id']}'");
    }
    public function setActionDate()
    {
        $this->_db->query("UPDATE `PointList` SET `action_date`=NOW() WHERE `Address`='{$this->address}'");
    }
    public function lastActionPastTime()
    {
        $point = $this->getMe();

        if(!$point['action_date']) {
            return null;
        }

        return time() - strtotime($point['action_date']);
    }
    public function canInit()
    {
        $point = $this->getMe();
        return $point['canInit'] == 1;
    }
    public function setCanInit($status)
    {
        $this->_db->query("UPDATE `PointList` SET `canInit`='{$status}' WHERE `Address`='{$this->address}'");
    }
    public function lastListenUpdate()
    {
        $this->_db->query("UPDATE `PointList` SET `last_listen`=NOW() WHERE `Address`='{$this->address}'");
    }
    public function setTeamScore($pointId, $teamPointTake)
    {
        $this->_db->query("UPDATE `PointList` SET `{$teamPointTake}`=`{$teamPointTake}`+1 WHERE `id`='{$pointId}'");
    }
    public function setTimeInPoint($pointId, $time)
    {
        $this->_db->query("UPDATE `PointList` SET `time`='{$time}' WHERE `id`='{$pointId}'");
    }
    public function resetPointTime($pointId)
    {
        $this->_db->query("UPDATE `PointList` SET `time`='0' WHERE `pointId`='{$pointId}'");
    }
    public function setHealth($Health)
    {
        $this->_db->query("UPDATE `PointList` SET `health`='{$Health}' WHERE `Address`='{$this->address}'");
    }
    public function setTakeTager($tagerId)
    {
        $this->_db->query("UPDATE `PointList` SET `action_date`=NOW(), `takeTagerId`='{$tagerId}' WHERE `Address`='{$this->address}'");
    }
    public function getTakeTager()
    {
        $point = $this->getMe();

        return $point['takeTagerId'];
    }
    public function getMe()
    {
        return $this->_db->getRow("SELECT * FROM `PointList` WHERE `Address`='{$this->address}'");
    }
    public function setStatus($status)
    {
        if($status == self::STATUS_OFF) {
            $this->_db->query("UPDATE `PointList` SET `status`=NULL WHERE `Address`='{$this->address}'");
            return;
        }
        $status = str_replace("\n","",$status);
        $status = (int)$status;
        $this->_db->query("UPDATE `PointList` SET `status`='{$status}' WHERE `Address`='{$this->address}'");
    }
    public function getStatus()
    {
        $point = $this->_db->getRow("SELECT * FROM `PointList` WHERE `Address`='{$this->address}'");
        return $point['status'];
    }
    public function reset() {
        $this->_db->query("UPDATE `PointList` SET `status`=NULL, `time`='0', `health`='0', `currPrioritet`=NULL, `action_date`=NULL, `NBlueTake`='0', `NRedTake`='0', `takeTagerId`=NULL, `canInit`='0' WHERE `Address`='{$this->address}'");
    }
    public function setPrioritet($prioritet) {
        $this->_db->query("UPDATE `PointList` SET `currPrioritet`='{$prioritet}' WHERE `Address`='{$this->address}'");
    }
}
