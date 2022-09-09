<?php
namespace entities;

class Base extends Dot {
    const BASE_RED = 1;
    const BASE_BLUE = 2;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const STATUS_ERROR = 2;

    const TYPE_OFF = NULL;
    const TYPE_PRESS_WAIT = 1;
    const TYPE_ENDLESS_REVIVAL = 2;
    const TYPE_REVIVAL_BY_TIME = 3;
    const TYPE_LIMITED_REVIVAL = 4;
    const TYPE_HEALING = 5;
    const TYPE_STOCK = 6;

    const INFINITY_TIME_WORK = 0;

    // Минимальный и максимальный интервал возрождения, для типа TYPE_REVIVAL_BY_TIME (возрождение по времени)
    const MIN_REBIRTH_INTERVAL = 10;
    const MAX_REBIRTH_INTERVAL = 3600;

    private $_base;

    public function setup($config)
    {
        if(DEBUG_GLOBAL === true) {
            echo 'База: "' . $this->name . '" инициализирована.<br />';
            return;
        }

        $this->curlContents($this->address . '/setup.php', 'GET', $config);
    }

    public function sendRessurection($missionConfig = [], $colorName)
    {
        $base = $this->getBase();
        $config = [
            'Type' => Base::TYPE_REVIVAL_BY_TIME,
            'id' => $base['id'],
            'ColorId' => $this->getBaseColorId($missionConfig['id']),
            'TimePushBase' => $missionConfig['TimePushBase'.$colorName],
            'Status' => '1'
        ];

        //$this->off();
        $this->setup($config);
        $this->lastRessurectionUpdate();
    }

    public function getResources()
    {
        $base = $this->_base = $this->_db->getRow("SELECT * FROM `BaseList` WHERE `Address`='{$this->address}'");
        return new BaseResources($base['resources']);
    }
    private function updateResources($resources)
    {
        $this->_db->query("UPDATE `BaseList` SET `resources`='{$resources}' WHERE `Address`='{$this->address}'");
    }
    public function setResourcesDefault($ressurections, $ammo) {
        $resources = $this->getResources();
        $resources->setResourcesDefault($ressurections, $ammo);

        $this->updateResources($resources->getJson());
    }
    public function addResource($resource)
    {
        $base = $this->getBase();
        $currResources = new BaseResources($base['resources']);


        if($resource['SendId']==BaseResources::TYPE_RESSURECTION) {
            $currResources->updateRessurections(1);
        } elseif($resource['SendId']==BaseResources::TYPE_AMMO) {
            $currResources->updateAmmo(1);
        } else {
            $currResources->achivments[] = ['id' => $resource['id'], 'name' => $resource['Name'], 'sendId' => $resource['SendId']];
        }

        $this->updateResources($currResources->getJson());

        return true;
    }
    public function removeResource($What) {
        $base = $this->getBase();
        $currResources = new BaseResources($base['resources']);

        if($What==1) {
            $currResources->updateRessurections(1, 'minus');
        } elseif($What==2) {
            $currResources->updateAmmo(1, 'minus');
        } else {
            array_shift($currResources->achivments);
        }


        $this->updateResources($currResources->getJson());

        return true;
    }
    public function stockReinit($missionConfig, $teamName)
    {
        $base = $this->getBase();
        $resources = $this->getResources();
        $achivements = $resources->getAchivments();

        $config = [
            'Type' => Base::TYPE_STOCK,
            'id' => $base['id'],
            'ColorId' => $this->getBaseColorId($missionConfig['id']),
            'TimePushBase' => $missionConfig['TimePushBase'.$teamName],
            'ReBase' => ($resources->getRessurections()!=0) ? 1 : 0,
            'WeaponBase' => ($resources->getAmmo()!=0) ? 1 : 0,
            'Achive' => ($achivements) ? $achivements[0]['sendId'] : 0,
        ];

        //$this->off();
        $this->setup($config);
    }

    public function timeOver()
    {
        $base = $this->getBase();

        if($base['TimeOver']!=1) {
            $this->_db->query("UPDATE `BaseList` SET `TimeOver`='1'  WHERE `Address`='{$this->address}'");

            $resources = $this->getResources();
            $resources->setResourcesDefault(0, 0);
            $resources->clearAchivements();
            $this->updateResources($resources->getJson());

            return $this->curlContents($this->address . '/Time_over.php');
        }
        return false;
    }

    public function getId()
    {
        $base = $this->getBase();
        return $base['id'];
    }
    public function isReady()
    {
        $base = $this->getBase();
        $status = $this->_db->getRow("SELECT * FROM `BaseList` WHERE `id`='{$base['id']}'");

        return ($status['isReady']==1);
    }
    public function getStatus()
    {
        $base = $this->getBase();
        return $base['status'];
    }
    public function getBase()
    {
        if(!$this->_base) {
            $this->_base = $this->_db->getRow("SELECT * FROM `BaseList` WHERE `Address`='{$this->address}'");
        }
        return $this->_base;
    }
    public function getBaseColorId($missionId)
    {
        $base = $this->getBase();

        $mission = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$missionId}'");
        $redBaseId = $mission['RedBaseId'];
        $blueBaseId = $mission['BlueBaseId'];

        if($base['id']==$redBaseId) {
            return Base::BASE_RED;
        }
        if($base['id']==$blueBaseId) {
            return Base::BASE_BLUE;
        }
    }

    public function ressurectIntervalCalculate($interval, $coef)
    {
        $interval = floor($interval * ($coef/100));


        if($interval<self::MIN_REBIRTH_INTERVAL) {
            $interval = self::MIN_REBIRTH_INTERVAL;
        }

        if($interval>self::MAX_REBIRTH_INTERVAL) {
            $interval = self::MAX_REBIRTH_INTERVAL;
        }

        return $interval;
    }
    public function ressurectIntervalCalculateGandicap($interval, $colorName, $scoreRed, $scoreBlue)
    {
        if($colorName=='Red') {
            if($scoreRed!=0 && $scoreBlue!=0) {
                $interval = $interval * ($scoreRed / $scoreBlue);
            }
        } else {
            if($scoreRed!=0 && $scoreBlue!=0) {
                $interval = $interval * ($scoreBlue / $scoreRed);
            }
        }

        echo '||'.$interval.'||';
        if($interval<self::MIN_REBIRTH_INTERVAL) {
            $interval = self::MIN_REBIRTH_INTERVAL;
        }

        if($interval>self::MAX_REBIRTH_INTERVAL) {
            $interval = self::MAX_REBIRTH_INTERVAL;
        }

        return $interval;
    }

    public function setType($type)
    {
        if($type === self::TYPE_OFF) {
            $this->_db->query("UPDATE `BaseList` SET `type`=NULL WHERE `Address`='{$this->address}'");
            return;
        }
        $this->_db->query("UPDATE `BaseList` SET `type`='{$type}' WHERE `Address`='{$this->address}'");
    }
    public function setStatus($status)
    {
        $this->_db->query("UPDATE `BaseList` SET `status`='{$status}' WHERE `Address`='{$this->address}'");
    }
    public function getNRessurections()
    {
        $this->_db->getRow("SELECT * FROM `BaseList` WHERE `Address`='{$this->address}'");
    }
    public function setNRessurections($count)
    {
        $this->_db->query("UPDATE `BaseList` SET `nRessurections`='{$count}' WHERE `Address`='{$this->address}'");
    }
    public function lastListenUpdate()
    {
        $this->_db->query("UPDATE `BaseList` SET `last_listen`=NOW() WHERE `Address`='{$this->address}'");
    }
    public function lastRessurectionUpdate()
    {
        $this->_db->query("UPDATE `BaseList` SET `last_ressurection`=NOW() WHERE `Address`='{$this->address}'");
    }
    public function lastRessurectionIntervalUpdate()
    {
        $this->_db->query("UPDATE `BaseList` SET `last_ressurection_interval_calculate`=NOW() WHERE `Address`='{$this->address}'");
    }
    public function isNotTimeOver()
    {
        $base = $this->getBase();
        return $base['TimeOver'] == 0;
    }
    public function getLastListen()
    {
        $base = $this->getBase();
        return $base['last_listen'];
    }
    public function getType()
    {
        $base = $this->getBase();
        return $base['type'];
    }
    public function getCurrentView()
    {
        $base = $this->getBase();
        return $base['currentView'];
    }
    public function setCurrentView($view)
    {
        $base = $this->getBase();
        $this->_db->query("UPDATE `BaseList` SET `currentView`='{$view}' WHERE `id`='{$base['id']}'");
    }
    public function removeCurrentView()
    {
        $base = $this->getBase();
        $this->_db->query("UPDATE `BaseList` SET `currentView`=NULL WHERE `id`='{$base['id']}'");
    }
    public function getRessurectionInterval()
    {
        $base = $this->getBase();
        return $base['ressurectionInterval'];
    }
    public function setRessurectionInterval($interval)
    {
        $base = $this->getBase();
        $this->_db->query("UPDATE `BaseList` SET `last_ressurection_interval_calculate`=NOW(), `ressurectionInterval`='{$interval}' WHERE `id`='{$base['id']}'");
    }

    public function getLastRessurectionTimePass()
    {
        $base = $this->getBase();
        if($base['last_ressurection']==null) {
            return false;
        }
        return time() - strtotime($base['last_ressurection']);
    }

    public function getLastRessurectionIntervalTimePass()
    {
        $base = $this->getBase();
        return time() - strtotime($base['last_ressurection_interval_calculate']);
    }


    public function isOff()
    {
        return $this->getType()==Base::TYPE_OFF;
    }
    public function isPressWait()
    {
        return $this->getType()==Base::TYPE_PRESS_WAIT;
    }
    public function isEndlessRevival()
    {
        return $this->getType()==Base::TYPE_ENDLESS_REVIVAL;
    }
    public function isRevivalByTime()
    {
        return $this->getType()==Base::TYPE_REVIVAL_BY_TIME;
    }
    public function isLimitedRevival()
    {
        return $this->getType()==Base::TYPE_LIMITED_REVIVAL;
    }
    public function isHealing()
    {
        return $this->getType()==Base::TYPE_HEALING;
    }
    public function isStock()
    {
        return $this->getType()==Base::TYPE_STOCK;
    }
}