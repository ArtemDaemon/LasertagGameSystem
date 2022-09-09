<?php
namespace entities;
use classes\Logger;

class Player {
    public $id;
    public $colorId;
    public $gameId;
    public $tagerId;


    private $_inited = false;
    private $_db;
    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function init($gameId, $colorId, $tagerId)
    {

        $this->tagerId = $tagerId;

        $playerData = $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `TagerId`='{$this->tagerId}' AND `Game`='{$gameId}'");


        $playerId = $playerData['Name'];
        if(!$playerData) {
            // Добавляем игрока
            $this->_db->query("INSERT INTO `PlayerNameList` VALUES('', '')");
            $playerId = $this->_db->insertId();
            $this->_db->query("INSERT INTO `PlayersList` VALUES('', '{$colorId}', '{$playerId}', '{$gameId}', '{$tagerId}', '0', '0', '0', NULL)");
        } else {
            // Если цвет игрока не совпадает, переинициализируем
            if($playerData['Color']!=$colorId) {
                $playerData['Color'] = $colorId;
                $this->_db->query("UPDATE `PlayersList` SET `Color`='{$colorId}' WHERE `id`='{$playerData['id']}'");
            }
        }

        $this->id = $playerId;
        $this->colorId = $colorId;
        $this->gameId = $gameId;
        $this->tagerId = $tagerId;

        $this->_inited = true;

        $this->createStat($gameId);
    }


    public function getName()
    {
        $this->checkInit();
        $playerName = $this->_db->getRow("SELECT * FROM `PlayerNameList` WHERE `id`='{$this->id}'");
        return $playerName['PlayerName'];
    }

    public function getTager()
    {
        $this->checkInit();
        $playersList = $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}'");

        return $playersList['TagerId'];
    }

    public function hasBomb()
    {
        $this->checkInit();
        $tagerBomb = $this->_db->getRow("SELECT * FROM `TagerBomb` WHERE `tagerId`='{$this->tagerId}'");

        return ($tagerBomb==true);
    }
    public function takeBomb($pointId = null)
    {

        // Все игроки команды
        $allTeammates = $this->_db->getAll("SELECT * FROM `PlayersList` WHERE `Color`='{$this->colorId}' AND `Game`='{$this->gameId}'");
        $ids = [];
        foreach($allTeammates as $teammate) {
            $ids[] = $teammate['TagerId'];
        }
        $ids = implode(',', $ids);

        // Удаляем первого игрока, если бомб уже 3
        $allBombs = $this->_db->getAll("SELECT * FROM `TagerBomb` WHERE `tagerId` IN ({$ids}) ORDER BY `add_date` ASC");
        if(count($allBombs)>=3) {
            $this->_db->query("DELETE FROM `TagerBomb` WHERE `tagerId`='{$allBombs[0]['tagerId']}'");
        }

        if(!$this->_db->getRow("SELECT * FROM `TagerBomb` WHERE `tagerId`='{$this->tagerId}'")) {
            $this->_db->query("INSERT INTO `TagerBomb` VALUES('' ,'{$pointId}' ,'{$this->tagerId}', NOW())");
        }
    }
    public function removeBomb()
    {
        $this->_db->query("DELETE FROM `TagerBomb` WHERE `tagerId`='{$this->tagerId}'");
        $this->_db->query("UPDATE `PlayerStat` SET `NSetupBomb`=`NSetupBomb`+1 WHERE `PlayerId`='{$this->id}'");
    }

    public function getTeam()
    {
        $this->checkInit();
        $team = $this->_db->getRow("SELECT * FROM `TeamColor` WHERE `id`='{$this->colorId}'");
        return $team;
    }

    public function getStat()
    {
        $this->checkInit();
        return $this->_db->getAll("SELECT * FROM `PlayerStat` WHERE `PlayerId`='{$this->id}' AND `GameListId`='{$this->gameId}'");
    }
    public function takedPointsCount()
    {
        $playersList = $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}'");

        return $playersList['pointsTake'];
    }

    public function createStat($gameId)
    {
        $this->checkInit();
        if(!$this->getStat()) {
            $this->_db->query("INSERT INTO `PlayerStat` VALUES('', '{$this->id}', '{$gameId}', '0', '0', '0', '0', '0', '0')");
        }
    }
    public function addScore($score)
    {
        $this->_db->query("UPDATE `PlayerStat` SET `Score`=`Score`+'{$score}' WHERE `PlayerId`='{$this->id}' AND `GameListId`='{$this->gameId}' LIMIT 1");
    }

    public function takePoint()
    {
        $this->_db->query("UPDATE `PlayerStat` SET `NTakePoint`=`NTakePoint`+1 WHERE `PlayerId`='{$this->id}' AND `GameListId`='{$this->gameId}' LIMIT 1");
    }
    public function takePointForCurrRound()
    {
        $this->_db->query("UPDATE `PlayersList` SET `pointsTake`=`pointsTake`+1 WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}' LIMIT 1");
    }

    public function flagCapture()
    {
        $this->_db->query("UPDATE `PlayerStat` SET `NTakePointFlag`=`NTakePointFlag`+1 WHERE `PlayerId`='{$this->id}' AND `GameListId`='{$this->gameId}'");
        $this->_db->query("UPDATE `PlayersList` SET `hasFlag`='0', `pointsTake`='0', `actionTime`=NULL, `flagsCaptured`=`flagsCaptured`+1 WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}'");
    }
    public function hasFlag()
    {
        return $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `Name`='{$this->id}' AND `hasFlag`='1' AND `Game`='{$this->gameId}'");
    }
    public function takeFlag()
    {
        $this->_db->query("UPDATE `PlayersList` SET `hasFlag`='1', `actionTime`=NOW() WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}'");
    }
    public function lostFlag()
    {
        $this->_db->query("UPDATE `PlayersList` SET `hasFlag`='0', `actionTime`=NULL WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}'");
    }
    public function nullPoints()
    {
        $this->_db->query("UPDATE `PlayersList` SET `pointsTake`='0' WHERE `Name`='{$this->id}' AND `Game`='{$this->gameId}'");
    }


    public function checkInit()
    {
        if(!$this->_inited) {
            throw new \Exception('Игрок не инициализирован');
        }

        return true;
    }
}