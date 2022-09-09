<?php 
//predifined fetch constants
define('MYSQL_BOTH',MYSQLI_BOTH);
define('MYSQL_NUM',MYSQLI_NUM);
define('MYSQL_ASSOC',MYSQLI_ASSOC);
	require "include/config.php";
	
	function executeNonQuery($query, $bRetId){
		
		global $db_host, $db_user, $db_pass, $db_name;
		
		$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
		$mysqli->set_charset("utf8");
		$mysqli->query($query);
		if ($bRetId) {
			$retId = $mysqli->insert_id;
		}
		$mysqli->close();
		if ($bRetId) {
			return $retId;
		}
	}
	
	
	function getSingleValue($query, $field){
		
		global $db_host, $db_user, $db_pass, $db_name;
		
		$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
		$mysqli->set_charset("utf8");
		$result = $mysqli->query($query);
		
		if ($result != null){
			$arr = $result->fetch_array(MYSQL_ASSOC);
			$result->close();
			$mysqli->close();
			return $arr[$field];
		}
		else{
			return null;
		}
	}
	
	function getArray($query){
		
		global $db_host, $db_user, $db_pass, $db_name;
		
		$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
		$mysqli->set_charset("utf8");
		
		$result = $mysqli->query($query);
		$retArray = array();
		
	    while($row = $result->fetch_array(MYSQL_ASSOC)) {
	    	$retArray[] = $row;
	    }
	    
	    $result->close();
		$mysqli->close();
		return $retArray;
	}
	
	switch(strtolower($_GET["action"])){
		
		case "getcommands":
			
			$query = "SELECT * FROM TeamColor";
			$commands_list = getArray($query);
			
		    echo json_encode($commands_list);
			break;
		case "getbombtypes":
			
			$query = "SELECT * FROM CatalogTypeBomb";
			$types_list = getArray($query);
			
		    echo json_encode($types_list);
			break;
		case "getplayerlist":
			
			$query = "SELECT * FROM PlayerNameList";
			$players_list = getArray($query);
			
		    echo json_encode($players_list);
			break;
		case "getmissionlist":
			
			$query = "SELECT * FROM MissionList WHERE Name IS NOT NULL AND Name != '' ORDER BY id DESC";
			$mission_list = getArray($query);
			
		    echo json_encode($mission_list);
			break;
		case "getwhocan":
			
			$query = "SELECT * FROM WhoCan";
			$whocan_list = getArray($query);
			
		    echo json_encode($whocan_list);
			break;
			
		case "getbases":
			
			$query = "SELECT * FROM BaseList ORDER BY id DESC";
			$bases_list = getArray($query);
			
		    echo json_encode($bases_list);
			break;
		
		case "getbase":
			
			$base_id = $_GET["bid"];
			if (is_numeric($base_id)){
				
				$query = "SELECT * FROM BaseList WHERE id=$base_id";
				$base_item = getArray($query);
				
				if (count($base_item) > 0)
					echo json_encode($base_item[0]);
				else
					echo "{}";
			}
			else{
				echo "{}";
			}
			
			break;
			
		case "addbase":
			
			$name = $_GET["name"];
			$address = $_GET["address"];
			if ($address == null) $address = "";
			
			if (isset($name)){
				
				$query = "INSERT INTO BaseList (name, address) VALUES ('$name', '$address')";
				executeNonQuery($query, false);
			}
			break;
		case "setbase":
			
			$base_id = $_GET["bid"];
			$name = $_GET["name"];
			$address = $_GET["address"];
			
			if (is_numeric($base_id) && isset($name) && isset($address)){
				$query = "UPDATE BaseList SET name='$name', address='$address' WHERE id=$base_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deletebase":
			
			$base_id = $_GET["bid"];
			
			if (is_numeric($base_id)){
				
				$query = "DELETE FROM BaseList WHERE id=$base_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		
		case "getpoints":
			
			$query = "SELECT * FROM PointList ORDER BY id DESC";
			$points_list = getArray($query);
			
		    echo json_encode($points_list);
			break;
		
		case "addpoint":
			
			$name = $_GET["name"];
			$address = $_GET["address"];
			if ($address == null) $address = "";
			
			if (isset($name)){
				
				$query = "INSERT INTO PointList (name, address) VALUES ('$name', '$address')";
				executeNonQuery($query, false);
			}
			break;
		case "setpoint":
			
			$point_id = $_GET["pid"];
			$name = $_GET["name"];
			$address = $_GET["address"];
			
			if (is_numeric($point_id) && isset($name)){
				$query = "UPDATE PointList SET name='$name', address='$address' WHERE id=$point_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deletepoint":
			
			$point_id = $_GET["pid"];
			
			if (is_numeric($point_id)){
				
				$query = "DELETE FROM PointList WHERE id=$point_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		
		case "getpresets":
			
			$query = "SELECT * FROM PresetMain ORDER BY id DESC";
			$presets_list = getArray($query);
			
		    echo json_encode($presets_list);
			break;
		
		case "addpreset":
			
			$name = $_GET["name"];
			$sum = $_GET["sum"];
			$time = $_GET["time"];
			$armor = $_GET["armor"];
			
			if (!is_numeric($sum)) $sum = 0;
			if (!is_numeric($time)) $time = 0;
			if (!is_numeric($armor)) $armor = 0;
			
			if (isset($name)){
				
				$query = "INSERT INTO PresetMain (NamePreset, SumDamage, TimeResetSum, Armor) VALUES ('$name', $sum, $time, $armor)";
				executeNonQuery($query, false);
			}
			break;
		case "setpreset":
			
			$preset_id = $_GET["pid"];
			$name = $_GET["name"];
			$sum = $_GET["sum"];
			$time = $_GET["time"];
			$armor = $_GET["armor"];
			
			if (!is_numeric($sum)) $sum = 0;
			if (!is_numeric($time)) $time = 0;
			if (!is_numeric($armor)) $armor = 0;
			
			if (is_numeric($preset_id) && isset($name)){
				$query = "UPDATE PresetMain SET NamePreset='$name', SumDamage=$sum, TimeResetSum=$time, Armor=$armor WHERE id=$preset_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deletepreset":
			
			$preset_id = $_GET["pid"];
			
			if (is_numeric($preset_id)){
				
				$query = "DELETE FROM PresetMain WHERE id=$preset_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		case "getweapons":
			
			$query = "SELECT * FROM WeaponList ORDER BY id DESC";
			$weapons_list = getArray($query);
			
		    echo json_encode($weapons_list);
			break;
		
		case "addweapon":
			
			$name = $_GET["name"];
			$tager_id = $_GET["tid"];
			
			if (isset($name) && is_numeric($tager_id)){
				
				$query = "INSERT INTO WeaponList (name, TagerId) VALUES ('$name', $tager_id)";
				executeNonQuery($query, false);
			}
			break;
		case "setweapon":
			
			$weapon_id = $_GET["wid"];
			$name = $_GET["name"];
			$tager_id = $_GET["tid"];
			
			if (is_numeric($weapon_id) && isset($name) && is_numeric($tager_id)){
				$query = "UPDATE WeaponList SET name='$name', TagerId=$tager_id WHERE id=$weapon_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deleteweapon":
			
			$weapon_id = $_GET["wid"];
			
			if (is_numeric($weapon_id)){
				
				$query = "DELETE FROM WeaponList WHERE id=$weapon_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		case "getresources":
			
			$query = "SELECT r.*, t.name as Type FROM ResourceList r INNER JOIN ResourceType t ON t.id = r.TypeId ORDER BY r.id DESC";
			$resources_list = getArray($query);
			
		    echo json_encode($resources_list);
			break;
		
		case "getresourcetypes":
			
			$query = "SELECT * FROM ResourceType ORDER BY id DESC";
			$resources_list = getArray($query);
			
		    echo json_encode($resources_list);
			break;
		
		
		case "addresource":
			
			$name = $_GET["name"];
			$type_id = $_GET["tid"];
			
			if (isset($name) && is_numeric($type_id)){
				
				$query = "INSERT INTO ResourceList (name, TypeId) VALUES ('$name', $type_id)";
				executeNonQuery($query, false);
			}
			break;
		case "setresource":
			
			$resource_id = $_GET["rid"];
			$name = $_GET["name"];
			$type_id = $_GET["tid"];
			
			if (is_numeric($resource_id) && isset($name) && is_numeric($type_id)){
				$query = "UPDATE ResourceList SET name='$name', TypeId=$type_id WHERE id=$resource_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deleteresource":
			
			$resource_id = $_GET["rid"];
			
			if (is_numeric($resource_id)){
				
				$query = "DELETE FROM ResourceList WHERE id=$resource_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		case "getgames":
			
			$query = "SELECT * FROM GameList ORDER BY id DESC";
			$games_list = getArray($query);
			
		    echo json_encode($games_list);
			break;
		
		case "addgame":
			
			$name = $_GET["name"];
			$number = $_GET["num"];
			
			if (isset($name)){
				
				$query = "INSERT INTO GameList (name, N) VALUES ('$name', '$number')";
				executeNonQuery($query, false);
			}
			break;
		case "setgame":
			
			$game_id = $_GET["gid"];
			$name = $_GET["name"];
			$number = $_GET["num"];
			
			if (is_numeric($game_id) && isset($name)){
				$query = "UPDATE GameList SET name='$name', N='$number' WHERE id=$game_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deletegame":
			
			$game_id = $_GET["gid"];
			
			if (is_numeric($game_id)){
				
				$query = "DELETE FROM Rounds WHERE GameId=$game_id";
				executeNonQuery($query, false);
				
				$query = "DELETE FROM PlayersList WHERE Game=$game_id";
				executeNonQuery($query, false);
				
				$query = "DELETE FROM PlayerStat WHERE GameListId=$game_id";
				executeNonQuery($query, false);
				
				$query = "DELETE FROM GameList WHERE id=$game_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		case "getgameplayers":
			
			$game_id = $_GET["gid"];
			
			if (is_numeric($game_id)){
				$query = "SELECT p.*, n.PlayerName, c.Name as TeamColor, g.Name as Game FROM PlayersList p 
							INNER JOIN TeamColor c ON c.id = p.Color
						    INNER JOIN PlayerNameList n ON n.id = p.Name
						    INNER JOIN GameList g ON g.id = p.Game WHERE Game=$game_id ORDER BY id DESC";
				$games_list = getArray($query);
				
			    echo json_encode($games_list);
		    }
		    else{
		    	echo "[]";
		    }
			break;
		
		case "getplayerbyname":
			
			$name = $_GET["name"];
			$add = $_GET["add"];
			
			if (isset($name)){
				$query = "SELECT id FROM PlayerNameList WHERE PlayerName='$name'";
				$player_id = getSingleValue($query, "id");
				
				if ($player_id == null){
			    	if ($add == "1"){
			    		$query = "INSERT INTO PlayerNameList (PlayerName) VALUES ('$name')";
						$player_id = executeNonQuery($query, true);
			    	}
			    }
			    
			    echo "{\"id\":$player_id, \"name\": \"$name\"}";
		    }
		    else{
		    	echo "{}";
		    }
			break;
		
		case "addgameplayer":
			
			$color_id = $_GET["cid"];
			$game_id = $_GET["gid"];
			$player_id = $_GET["pid"];
			$tager_id = $_GET["tid"];
			
			if (is_numeric($color_id) && is_numeric($game_id) && is_numeric($player_id) && is_numeric($tager_id)){
				
				$query = "INSERT INTO PlayersList (Color, Name, Game, TagerId) VALUES ($color_id, $player_id, $game_id, $tager_id)";
				executeNonQuery($query, false);
		    }
			break;
		
		case "setgameplayer":
			
			$pl_id = $_GET["plid"];
			$color_id = $_GET["cid"];
			$game_id = $_GET["gid"];
			$player_id = $_GET["pid"];
			$tager_id = $_GET["tid"];
			
			if (is_numeric($color_id) && is_numeric($game_id) && is_numeric($player_id) && is_numeric($tager_id) && is_numeric($pl_id)){
				
				$query = "UPDATE PlayersList SET Color=$color_id, Name=$player_id, Game=$game_id, TagerId=$tager_id WHERE id=$pl_id";
				executeNonQuery($query, false);
		    }
			break;
			
		case "deletegameplayer":
			
			$game_id = $_GET["gid"];
			$player_id = $_GET["pid"];
			
			if (is_numeric($game_id) && is_numeric($player_id)){
				$query = "DELETE FROM PlayersList WHERE Name=$player_id AND Game=$game_id";
				executeNonQuery($query, false);
		    }
			break;
		
		case "getgameresults":
			
			$game_id = $_GET["gid"];
			
			if (is_numeric($game_id)){
				$query = "SELECT r.*, m.Name FROM Rounds r
						INNER JOIN MissionList m ON m.id = r.MissionId
						WHERE GameId=$game_id ORDER BY id DESC";
				
				$result_list = getArray($query);
				
			    echo json_encode($result_list);
		    }
		    else{
		    	echo "[]";
		    }
			break;
		
		case "addgameresult":
			
			$game_id = $_GET["gid"];
			$mission_id = $_GET["mid"];
			$scoreRed = $_GET["sr"];
			$scoreBlue = $_GET["sb"];
			$whoWin = $_GET["w"];
			$draw = $_GET["d"];
			if ($draw != "1") $draw = "0";
			
			if (is_numeric($game_id) && is_numeric($mission_id) && is_numeric($scoreRed) && is_numeric($scoreBlue) && is_numeric($whoWin)){
				
				$query = "INSERT INTO Rounds (GameId, MissionId, ScoreRed, ScoreBlue, WhoWin, Draw) VALUES ($game_id, $mission_id, $scoreRed, $scoreBlue, $whoWin, $draw)";
				executeNonQuery($query, false);
		    }
			break;
		
		case "setgameresult":
			
			$result_id = $_GET["grid"];
			$game_id = $_GET["gid"];
			$mission_id = $_GET["mid"];
			$scoreRed = $_GET["sr"];
			$scoreBlue = $_GET["sb"];
			$whoWin = $_GET["w"];
			$draw = $_GET["d"];
			if ($draw != "1") $draw = "0";
			
			if (is_numeric($result_id) && is_numeric($game_id) && is_numeric($mission_id) && is_numeric($scoreRed) && is_numeric($scoreBlue) && is_numeric($whoWin)){
				
				$query = "UPDATE Rounds SET GameId=$game_id, MissionId=$mission_id, ScoreRed=$scoreRed, ScoreBlue=$scoreBlue, WhoWin=$whoWin, Draw=$draw WHERE id=$result_id";
				executeNonQuery($query, false);
			}
			break;
		
		case "deletegameresult":
			
			$result_id = $_GET["grid"];
			
			if (is_numeric($result_id)){
				$query = "DELETE FROM Rounds WHERE id=$result_id";
				executeNonQuery($query, false);
		    }
			break;
			
		case "getgamestats":
			
			$game_id = $_GET["gid"];
			
			if (is_numeric($game_id)){
				$query = "SELECT s.*, p.PlayerName, l.Color, c.Name as ColorName, w.Name as WeaponName FROM PlayerStat s
					INNER JOIN PlayerNameList p ON p.id = s.PlayerId
					LEFT JOIN PlayersList l ON l.Name = s.PlayerId
                    LEFT JOIN TeamColor c ON c.id = l.Color
                    LEFT JOIN WeaponList w ON w.TagerId = l.TagerId
					WHERE GameListId=$game_id GROUP BY s.PlayerId ORDER BY id DESC";
				$stats_list = getArray($query);
			    echo json_encode($stats_list);
		    }
		    else{
		    	echo "[]";
		    }
			break;
		
		case "getsoundsround":
			
			$query = "SELECT * FROM SoundRound ORDER BY NamePreset";
			$sounds_list = getArray($query);
			
		    echo json_encode($sounds_list);
			break;
		
		case "addsoundround":
			
			$NamePreset = $_GET["name"];
			$BackSound = $_GET["bs"];
			$BackSound10 = $_GET["bs10"];
			$RedReady = $_GET["rr"];
			$BlueReady = $_GET["br"];
			$RoundStart = $_GET["rstart"];
			$RedWin = $_GET["rw"];
			$BlueWin = $_GET["bw"];
			$Draw = $_GET["d"];
			$RoundStop = $_GET["rstop"];
			$RoundComplete = $_GET["rc"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundRound (NamePreset, BackSound, BackSound10, RedReady, BlueReady, RoundStart, RedWin, BlueWin, Draw, RoundStop, RoundComplete) VALUES 
					('$NamePreset', '$BackSound', '$BackSound10', '$RedReady', '$BlueReady', '$RoundStart', '$RedWin', '$BlueWin', '$Draw', '$RoundStop', '$RoundComplete')";
				executeNonQuery($query, false);
			}
			break;
		case "setsoundround":
			
			$soundround_id = $_GET["srid"];
			$NamePreset = $_GET["name"];
			$BackSound = $_GET["bs"];
			$BackSound10 = $_GET["bs10"];
			$RedReady = $_GET["rr"];
			$BlueReady = $_GET["br"];
			$RoundStart = $_GET["rstart"];
			$RedWin = $_GET["rw"];
			$BlueWin = $_GET["bw"];
			$Draw = $_GET["d"];
			$RoundStop = $_GET["rstop"];
			$RoundComplete = $_GET["rc"];
			
			if (is_numeric($soundround_id) && isset($NamePreset)){
				$query = "UPDATE SoundRound SET NamePreset='$NamePreset', BackSound='$BackSound', BackSound10='$BackSound10', RedReady='$RedReady', BlueReady='$BlueReady', 
							RoundStart='$RoundStart', RedWin='$RedWin', BlueWin='$BlueWin', Draw='$Draw', RoundStop='$RoundStop', RoundComplete='$RoundComplete' WHERE id=$soundround_id";
				
				executeNonQuery($query, false);
			}
			break;
		
		case "deletesoundround":
			
			$soundround_id = $_GET["srid"];
			
			if (is_numeric($soundround_id)){
				
				$query = "DELETE FROM SoundRound WHERE id=$soundround_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		case "getsoundspoint1":
			
			$query = "SELECT s.*, p.Name AS PointName FROM SoundPoint1 s INNER JOIN PointList p ON p.id=s.PointId ORDER BY s.NamePreset, PointName";
			$sounds_list = getArray($query);
			
		    echo json_encode($sounds_list);
			break;
		
		
		case "addsoundpoint1":
			
			$NamePreset = $_GET["name"];
			$PointId = $_GET["pid"];
			$RedSound = $_GET["rs"];
			$BlueSound = $_GET["bs"];
			
			if (isset($NamePreset) && is_numeric($PointId)){
				
				$query = "INSERT INTO SoundPoint1 (NamePreset, PointId, RedSound, BlueSound) VALUES ('$NamePreset', $PointId, '$RedSound', '$BlueSound')";
				executeNonQuery($query, false);
			}
			break;
		case "setsoundpoint1":
			
			$soundpoint1_id = $_GET["spid"];
			$NamePreset = $_GET["name"];
			$PointId = $_GET["pid"];
			$RedSound = $_GET["rs"];
			$BlueSound = $_GET["bs"];
			
			if (is_numeric($soundpoint1_id) && isset($NamePreset) && is_numeric($PointId)){
				$query = "UPDATE SoundPoint1 SET NamePreset='$NamePreset', PointId=$PointId, RedSound='$RedSound', BlueSound='$BlueSound' WHERE id=$soundpoint1_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundpoint1":
			
			$soundpoint1_id = $_GET["spid"];
			
			if (is_numeric($soundpoint1_id)){
				
				$query = "DELETE FROM SoundPoint1 WHERE id=$soundpoint1_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		case "getsoundpoint2":
			
			$query = "SELECT * FROM SoundPoint2 ORDER BY NamePreset";
			$sounds_list = getArray($query);
			
		    echo json_encode($sounds_list);
			break;
		
		
		case "addsoundpoint2":
			
			$NamePreset = $_GET["name"];
			$RedLead = $_GET["rl"];
			$BlueLead = $_GET["bl"];
			$Period = $_GET["p"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundPoint2 (NamePreset, RedLead, BlueLead, Period) VALUES ('$NamePreset', '$RedLead', '$BlueLead', '$Period')";
				executeNonQuery($query, false);
			}
			break;
		case "setsoundpoint2":
			
			$soundpoint2_id = $_GET["spid"];
			$NamePreset = $_GET["name"];
			$RedLead = $_GET["rl"];
			$BlueLead = $_GET["bl"];
			$Period = $_GET["p"];
			
			if (is_numeric($soundpoint2_id) && isset($NamePreset)){
				$query = "UPDATE SoundPoint2 SET NamePreset='$NamePreset', RedLead='$RedLead', BlueLead='$BlueLead', Period='$Period' WHERE id=$soundpoint2_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundpoint2":
			
			$soundpoint2_id = $_GET["spid"];
			
			if (is_numeric($soundpoint2_id)){
				
				$query = "DELETE FROM SoundPoint2 WHERE id=$soundpoint2_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		case "getsoundstakeflag":
			
			$query = "SELECT * FROM SoundTakeFlag ORDER BY NamePreset";
			$soundtakeflags_list = getArray($query);
			
		    echo json_encode($soundtakeflags_list);
			break;
		
		case "addsoundtakeflag":
			
			$NamePreset = $_GET["name"];
			$RedTakeFlag = $_GET["rtf"];
			$RedMissFlag = $_GET["rmf"];
			$RedComplete = $_GET["rc"];
			$RedTakePoint = $_GET["rtp"];
			$BlueTakeFlag = $_GET["btf"];
			$BlueMissFlag = $_GET["bmf"];
			$BlueComplete = $_GET["bc"];
			$BlueTakePoint = $_GET["btp"];
			$RedFinish = $_GET["rf"];
			$BlueFinish = $_GET["bf"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundTakeFlag (NamePreset, RedTakeFlag, RedMissFlag, RedComplete, RedTakePoint, BlueTakeFlag, BlueMissFlag, BlueComplete, BlueTakePoint) 
				VALUES ('$NamePreset', '$RedTakeFlag', '$RedMissFlag', '$RedComplete', '$RedTakePoint', '$BlueTakeFlag', '$BlueMissFlag', '$BlueComplete', '$BlueTakePoint', '$RediFinish', '$BlueFinish')";
				executeNonQuery($query, false);
			}
			break;
			
		case "setsoundtakeflag":
			
			$soundtakeflag_id = $_GET["stfid"];
			$NamePreset = $_GET["name"];
			$RedTakeFlag = $_GET["rtf"];
			$RedMissFlag = $_GET["rmf"];
			$RedComplete = $_GET["rc"];
			$RedTakePoint = $_GET["rtp"];
			$BlueTakeFlag = $_GET["btf"];
			$BlueMissFlag = $_GET["bmf"];
			$BlueComplete = $_GET["bc"];
			$BlueTakePoint = $_GET["btp"];
			$RedFinish = $_GET["rf"];
			$BlueFinish = $_GET["bf"];
			
			if (is_numeric($soundtakeflag_id) && isset($NamePreset)){
				$query = "UPDATE SoundTakeFlag SET NamePreset='$NamePreset', RedTakeFlag='$RedTakeFlag', RedMissFlag='$RedMissFlag', RedComplete='$RedComplete', RedTakePoint='$RedTakePoint', 
							BlueTakeFlag='$BlueTakeFlag', BlueMissFlag='$BlueMissFlag', BlueComplete='$BlueComplete', BlueTakePoint='$BlueTakePoint', RedReadySetup='$RedFinish', BlueReadySetup='$BlueFinish' WHERE id=$soundtakeflag_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundtakeflag":
			
			$soundtakeflag_id = $_GET["stfid"];
			
			if (is_numeric($soundtakeflag_id)){
				
				$query = "DELETE FROM SoundTakeFlag WHERE id=$soundtakeflag_id";
				executeNonQuery($query, false);
				
			}
			break;

		
		
		case "getsoundtimes":
			
			$query = "SELECT s.*, p.NamePreset FROM SoundTime s INNER JOIN SoundRound p ON p.id = s.NamePresetId ORDER BY NamePreset";
			$soundtimes_list = getArray($query);
			
		    echo json_encode($soundtimes_list);
			break;
		
		case "addsoundtime":
			
			$NamePresetId = $_GET["name"];
			$TimeToEnd = $_GET["t"];
			$File = $_GET["f"];
			
			if (isset($NamePresetId)){
				
				$query = "INSERT INTO SoundTime (NamePresetId, TimeToEnd, File) VALUES ('$NamePresetId', $TimeToEnd, '$File')";
				executeNonQuery($query, false);
			}
			break;
			
		case "setsoundtime":
			
			$soundtime_id = $_GET["stid"];
			$NamePresetId = $_GET["name"];
			$TimeToEnd = $_GET["t"];
			$File = $_GET["f"];
			
			if (is_numeric($soundtime_id) && isset($NamePresetId)){
				$query = "UPDATE SoundTime SET NamePresetId='$NamePresetId', TimeToEnd=$TimeToEnd, File='$File' WHERE id=$soundtime_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundtime":
			
			$soundtime_id = $_GET["stid"];
			
			if (is_numeric($soundtime_id)){
				
				$query = "DELETE FROM SoundTime WHERE id=$soundtime_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		
		
		case "getsoundtakehome":
			
			$query = "SELECT s.*, p.Name AS PointName FROM SoundTakeHome s INNER JOIN PointList p ON p.id=s.PointId ORDER BY s.NamePreset, PointName";
			$soundtakehomes_list = getArray($query);
			
		    echo json_encode($soundtakehomes_list);
			break;
		
		case "addsoundtakehome":
			
			$NamePreset = $_GET["name"];
			$PointId = $_GET["pid"];
			$File = $_GET["f"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundTakeHome (NamePreset, PointId, File) VALUES ('$NamePreset', $PointId, '$File')";
				executeNonQuery($query, false);
			}
			break;
			
		case "setsoundtakehome":
			
			$soundtakehome_id = $_GET["sthid"];
			$NamePreset = $_GET["name"];
			$PointId = $_GET["pid"];
			$File = $_GET["f"];
			
			if (is_numeric($soundtakehome_id) && isset($NamePreset) && is_numeric($PointId)){
				$query = "UPDATE SoundTakeHome SET NamePreset='$NamePreset', PointId=$PointId, File='$File' WHERE id=$soundtakehome_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundtakehome":
			
			$soundtakehome_id = $_GET["sthid"];
			
			if (is_numeric($soundtakehome_id)){
				
				$query = "DELETE FROM SoundTakeHome WHERE id=$soundtakehome_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		
		case "getsoundsurv":
			
			$query = "SELECT * FROM SoundSurv ORDER BY NamePreset";
			$soundsurvs_list = getArray($query);
			
		    echo json_encode($soundsurvs_list);
			break;
		
		case "addsoundsurv":
			
			$NamePreset = $_GET["name"];
			$File = $_GET["f"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundSurv (NamePreset, File) VALUES ('$NamePreset', '$File')";
				executeNonQuery($query, false);
			}
			break;
			
		case "setsoundsurv":
			
			$soundsurv_id = $_GET["ssid"];
			$NamePreset = $_GET["name"];
			$File = $_GET["f"];
			
			if (is_numeric($soundsurv_id) && isset($NamePreset)){
				$query = "UPDATE SoundSurv SET NamePreset='$NamePreset', File='$File' WHERE id=$soundsurv_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundsurv":
			
			$soundsurv_id = $_GET["ssid"];
			
			if (is_numeric($soundsurv_id)){
				
				$query = "DELETE FROM SoundSurv WHERE id=$soundsurv_id";
				executeNonQuery($query, false);
				
			}
			break;
		
		
		case "getsoundssetupbomb":
			
			$query = "SELECT * FROM SoundSetupBomb ORDER BY NamePreset";
			$soundsetupbombs_list = getArray($query);
			
		    echo json_encode($soundsetupbombs_list);
			break;
		
		case "addsoundsetupbomb":
			
			$NamePreset = $_GET["name"];
			$RedTakeBomb = $_GET["rtb"];
			$RedSetupBomb = $_GET["rsb"];
			$RedMakeBomb = $_GET["rmb"];
			$RedLostBomb = $_GET["rlb"];
			$RedDefuse = $_GET["rd"];
			$BlueTakeBomb = $_GET["btb"];
			$BlueSetupBomb = $_GET["bsb"];
			$BlueMakeBomb = $_GET["bmb"];
			$BlueLostBomb = $_GET["blb"];
			$BlueDefuse = $_GET["bd"];
			$Explosion = $_GET["e"];
			$Beep = $_GET["b"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundSetupBomb (NamePreset, RedTakeBomb, RedSetupBomb, RedMakeBomb, RedLostBomb, RedDefuse, BlueTakeBomb, BlueSetupBomb, 
							BlueMakeBomb, BlueLostBomb, BlueDefuse, Explosion, Beep) VALUES ('$NamePreset', '$RedTakeBomb', '$RedSetupBomb', '$RedMakeBomb','$RedLostBomb', '$RedDefuse', 
							'$BlueTakeBomb', '$BlueSetupBomb', '$BlueMakeBomb', '$BlueLostBomb', '$BlueDefuse', '$Explosion', '$Beep')";
				executeNonQuery($query, false);
			}
			break;
			
		case "setsoundsetupbomb":
			
			$soundsetupbomb_id = $_GET["ssbid"];
			$NamePreset = $_GET["name"];
			$RedTakeBomb = $_GET["rtb"];
			$RedSetupBomb = $_GET["rsb"];
			$RedMakeBomb = $_GET["rmb"];
			$RedLostBomb = $_GET["rlb"];
			$RedDefuse = $_GET["rd"];
			$BlueTakeBomb = $_GET["btb"];
			$BlueSetupBomb = $_GET["bsb"];
			$BlueMakeBomb = $_GET["bmb"];
			$BlueLostBomb = $_GET["blb"];
			$BlueDefuse = $_GET["bd"];
			$Explosion = $_GET["e"];
			$Beep = $_GET["b"];
			
			if (is_numeric($soundsetupbomb_id) && isset($NamePreset)){
				$query = "UPDATE SoundSetupBomb SET NamePreset='$NamePreset', RedTakeBomb='$RedTakeBomb', RedSetupBomb='$RedSetupBomb', RedMakeBomb='$RedMakeBomb', RedLostBomb='$RedLostBomb', 
					RedDefuse='$RedDefuse', BlueTakeBomb='$BlueTakeBomb', BlueSetupBomb='$BlueSetupBomb', BlueMakeBomb='$BlueMakeBomb', BlueLostBomb='$BlueLostBomb', 
					BlueDefuse='$BlueDefuse', Explosion='$Explosion', Beep='$Beep' WHERE id=$soundsetupbomb_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundsetupbomb":
			
			$soundsetupbomb_id = $_GET["ssbid"];
			
			if (is_numeric($soundsetupbomb_id)){
				
				$query = "DELETE FROM SoundSetupBomb WHERE id=$soundsetupbomb_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		case "getsoundsbase":
			
			$query = "SELECT * FROM SoundBase ORDER BY NamePreset";
			$sounds_list = getArray($query);
			
		    echo json_encode($sounds_list);
			break;
		case "addsoundbase":
			
			$NamePreset = $_GET["name"];
			$ReFillReaspawn = $_GET["fr"];
			$EmptyRespawn = $_GET["er"];
			
			if (isset($NamePreset)){
				
				$query = "INSERT INTO SoundBase (NamePreset, ReFillReaspawn, EmptyRespawn) VALUES ('$NamePreset', '$ReFillReaspawn', '$EmptyRespawn')";
				executeNonQuery($query, false);
			}
			break;
		case "setsoundbase":
			
			$soundbase_id = $_GET["sbid"];
			$NamePreset = $_GET["name"];
			$ReFillReaspawn = $_GET["fr"];
			$EmptyRespawn = $_GET["er"];
			
			if (is_numeric($soundbase_id) && isset($NamePreset)){
				$query = "UPDATE SoundBase SET NamePreset='$NamePreset', ReFillReaspawn='$ReFillReaspawn', EmptyRespawn='$EmptyRespawn' WHERE id=$soundbase_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundbase":
			
			$soundbase_id = $_GET["sbid"];
			
			if (is_numeric($soundbase_id)){
				
				$query = "DELETE FROM SoundBase WHERE id=$soundbase_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		case "getsoundsresoursecome":
			
			$query = "SELECT s.*, r.Name AS ResourceName, b.NamePreset AS SoundBaseName FROM `SoundResourseCome` s 
						INNER JOIN ResourceList r ON r.id = s.ResourseId
					    INNER JOIN SoundBase b ON b.id = s.IdSoundBase ORDER BY ResourceName";
			$sounds_list = getArray($query);
			
		    echo json_encode($sounds_list);
			break;
		case "addsoundresoursecome":
			
			$ResourseId = $_GET["rid"];
			$File = $_GET["f"];
			$IdSoundBase = $_GET["sbid"];
			
			if (isset($File)){
				
				$query = "INSERT INTO SoundResourseCome (ResourseId, File, IdSoundBase) VALUES ('$ResourseId', '$File', '$IdSoundBase')";
				executeNonQuery($query, false);
			}
			break;
		case "setsoundresoursecome":
			
			$soundresoursecome_id = $_GET["srcid"];
			$ResourseId = $_GET["rid"];
			$File = $_GET["f"];
			$IdSoundBase = $_GET["sbid"];
			
			if (is_numeric($soundresoursecome_id) && isset($File)){
				$query = "UPDATE SoundResourseCome SET ResourseId='$ResourseId', File='$File', IdSoundBase='$IdSoundBase' WHERE id=$soundresoursecome_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletesoundsresoursecome":
			
			$soundresoursecome_id = $_GET["srcid"];
			if (is_numeric($soundresoursecome_id)){
				
				$query = "DELETE FROM SoundResourseCome WHERE id=$soundresoursecome_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		case "getcatalogbasetypes":
			
			$query = "SELECT * FROM CatalogTypeBase ORDER BY id DESC";
			$resources_list = getArray($query);
			
		    echo json_encode($resources_list);
			break;
		
		case "getmissions":
			
			$query = "SELECT m.*, p.Name AS PointName FROM MissionList m
						INNER JOIN CatalogTypePoint p ON p.id = m.TypePoint
						WHERE m.Name IS NOT NULL AND m.Name <> '' ORDER BY m.id DESC";
			
			$missions = getArray($query);
			
		    echo json_encode($missions);
			break;
		case "addmission":
			
			/*$Name = $_GET["name"];
			$TypePoint = $_GET["tp"];
			$MaxTime = $_GET["mt"];
			$MaxScore = $_GET["ms"];
			$BlueLegend = $_GET["bl"];
			$RedLegend = $_GET["rl"];
			$IdSoundRound = $_GET["sr"];
			$SoundPointId = $_GET["sp"];
			$IdSoundBase = $_GET["sb"];*/
			
			
			$query = "INSERT INTO MissionList (";
			$values = " VALUES (";
			foreach ($_GET as $key => $value) {
				
				if ($key == "action") continue;
				
				$query .= $key . ", ";
				$values.= "'$value', ";
			}
			
			$query = rtrim($query, ", ");
			$values = rtrim($values, ", ");
			
			$query .= ") " . $values . ")"; 
			executeNonQuery($query, false);
			
			/*if (isset($Name)){
				
				$query = "INSERT INTO MissionList (Name, TypePoint, MaxTime, MaxScore, BlueLegend, RedLegend, IdSoundRound, SoundPointId, IdSoundBase) VALUES 
				('$Name', $TypePoint, $MaxTime, $MaxScore, '$BlueLegend', '$RedLegend', $IdSoundRound, $SoundPointId, $IdSoundBase)";
				
				executeNonQuery($query, false);
			}*/
			break;
			
		case "setmission":
			
			
			$mission_id = $_GET["mid"];
			
			$query = "UPDATE MissionList SET ";
			foreach ($_GET as $key => $value) {
				
				if ($key == "action" || $key == "mid") continue;
				
				$query .= $key . "='$value', " ;
			}
			
			$query = rtrim($query, ", ");
			$query .= " WHERE id=$mission_id";
			executeNonQuery($query, false);
			
			/*$mission_id = $_GET["mid"];
			$Name = $_GET["name"];
			$TypePoint = $_GET["tp"];
			$MaxTime = $_GET["mt"];
			$MaxScore = $_GET["ms"];
			$BlueLegend = $_GET["bl"];
			$RedLegend = $_GET["rl"];
			$IdSoundRound = $_GET["sr"];
			$SoundPointId = $_GET["sp"];
			$IdSoundBase = $_GET["sb"];
			
			
			if (is_numeric($mission_id) && isset($Name)){
				
				$query = "UPDATE MissionList SET Name='$Name', TypePoint=$TypePoint, MaxTime=$MaxTime, MaxScore=$MaxScore, BlueLegend='$BlueLegend', RedLegend='$RedLegend', 
							IdSoundRound=$IdSoundRound, SoundPointId=$SoundPointId, IdSoundBase=$IdSoundBase WHERE id=$mission_id";
				
				executeNonQuery($query, false);
			}*/
			break;
			
		case "getmissionbytypebase":
			
			$typebase_id = $_GET["tbid"];
			$command = $_GET["c"];
			
			if (is_numeric($typebase_id) && isset($command)){
				$query = "SELECT * FROM MissionList WHERE IdTypeBase$command=$typebase_id";
				$mission = getArray($query);
				
			    echo json_encode($mission);
		    }
		    else{
		    	echo "{}";
		    }
			break;
		
		case "setmissionbytypebase":
			
			$typebase_id = $_GET["tbid"];
			$command = $_GET["c"];
			$set = $_GET["set"];
			
			if (is_numeric($typebase_id) && isset($command) && isset($set)){
				$query = "UPDATE MissionList SET $set WHERE IdTypeBase$command=$typebase_id";
				executeNonQuery($query, false);
		    }
		    else{
		    	echo "{}";
		    }
			break;
		
		
		case "getpointtypes":
			
			$query = "SELECT * FROM CatalogTypePoint ORDER BY id DESC";
			$pointtypes_list = getArray($query);
			
		    echo json_encode($pointtypes_list);
			break;
			
		case "getpointtypesm1":
			
			$query = "SELECT * FROM TypePointM1 ORDER BY id DESC";
			$pointtypes_list = getArray($query);
			
		    echo json_encode($pointtypes_list);
			break;
			
		case "getmissionspointsetup":
			
			$mission_id = $_GET["mid"];
			
			if (is_numeric($mission_id)){
				$query = "SELECT ps.*, p.Name AS PointName, t.Name AS TypeWorkName, pr.NamePreset, r.Name AS ResourceTakeName, r2.Name as ResourceComliteName, c.Name as WhoseHomeName, b.Name as Bomb
					  		FROM MissionPointSetup ps
								LEFT JOIN PointList p ON p.id = ps.PointId
								LEFT JOIN TypePointM1 t ON t.id = ps.TypeWork
								LEFT JOIN PresetMain pr ON pr.id = ps.MainPresetId
								LEFT JOIN ResourceList r ON r.id = ps.ResourceTakeId
								LEFT JOIN ResourceList r2 ON r2.id = ps.ResourceIdComlite
								LEFT JOIN TeamColor c ON c.id = ps.WhoseHome
								LEFT JOIN CatalogTypeBomb b ON b.id = ps.TypeBomb
							WHERE MissionId=$mission_id ORDER BY ps.id DESC";
				
				$missionspointsetup = getArray($query);
				
			    echo json_encode($missionspointsetup);
		    }
		    else{
		    	echo "{}";
		    }
			break;
		
		case "addmissionspointsetup":
			
			$query = "INSERT INTO MissionPointSetup (";
			$values = " VALUES (";
			foreach ($_GET as $key => $value) {
				
				if ($key == "action") continue;
				if ($key == "Order") $key = "`Order`";
				
				$query .= $key . ", ";
				$values.= "'$value', ";
			}
			
			$query = rtrim($query, ", ");
			$values = rtrim($values, ", ");
			
			$query .= ") " . $values . ")"; 
			echo $query;
			executeNonQuery($query, false);
			break;
		
		case "setmissionspointsetup":
			
			$missionpointsetup_id = $_GET["mpsid"];
			
			$query = "UPDATE MissionPointSetup SET ";
			foreach ($_GET as $key => $value) {
				
				if ($key == "action" || $key == "mpsid") continue;
				if (strtolower($key) == "order") $key = "`Order`";
				
				$query .= $key . "='$value', " ;
			}
			
			$query = rtrim($query, ", ");
			$query .= " WHERE id=$missionpointsetup_id";
			executeNonQuery($query, false);
			break;
			
		case "deletemissionspointsetup":
			
			$missionpointsetup_id = $_GET["mpsid"];
			
			if (is_numeric($missionpointsetup_id)){
				$query = "DELETE FROM MissionPointSetup WHERE id=$missionpointsetup_id";
				executeNonQuery($query, false);
			}
			break;
			
		case "deletemission":
			
			$mission_id = $_GET["mid"];
			
			if (is_numeric($mission_id)){
				
				$query = "DELETE FROM Rounds WHERE Missionid=$mission_id";
				executeNonQuery($query, false);
				
				$query = "DELETE FROM MissionPointSetup WHERE Missionid=$mission_id";
				executeNonQuery($query, false);
				
				$query = "DELETE FROM MissionList WHERE id=$mission_id";
				executeNonQuery($query, false);
				
			}
			break;
			
		default:
			break;
	}
	
?>