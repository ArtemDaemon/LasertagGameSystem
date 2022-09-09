<!DOCTYPE html>
<html lang="ru" style=" overflow:  hidden;">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/style-blue-red.css">
    <link rel="stylesheet" href="/assets/css/style-statistic.css">
    <script src="/assets/js/jquery-2.1.3.min.js"></script>
    <script src="/assets/js/base.js"></script>
    <script src="/assets/js/common.js"></script>
    <title>База</title>
</head>
<body>
    <main class="b-main">


        <?php
//ПОдключение к БД для PDO
        $db_host = "localhost"; 
    $db_user = "phpmyadmin"; // Логин БД
    $db_password = "malina2018"; // Пароль БД
    $db_table = "dbname"; // Имя Таблицы БД



    // Подключение к базе данных    
    $db = new PDO("mysql:host=$db_host;dbname=$db_table;charset=UTF8", $db_user, $db_password); 
    $GameList = $db->query("SELECT * FROM  GameList WHERE opened='1'")->FETCH(PDO::FETCH_ASSOC);
///================ название игры
    $GameList_name = $GameList['Name'];
    $GameId =  $GameList['id'];


    $input = $db->query("SELECT * FROM  Rounds WHERE GameId='$GameId'")->FETCHALL(PDO::FETCH_ASSOC);

    for ($v=0; $v<count($input); $v++) {
        $Missionid = $input[$v]['MissionId'];   
        $MissionName = $db->query("SELECT Name FROM  MissionList WHERE id='$Missionid'")->FETCH(PDO::FETCH_ASSOC);
        $Score[$v]['MissionName']=$MissionName['Name'];
        $Score[$v]['ScoreRed']=$input[$v]['ScoreRed'];
        $Score[$v]['ScoreBlue']=$input[$v]['ScoreBlue'];

        if ($Score[$v]['ScoreBlue']<$Score[$v]['ScoreRed']) $Score[$v]['WhoWin'] = 1 ;
        if ($Score[$v]['ScoreBlue']>$Score[$v]['ScoreRed']) $Score[$v]['WhoWin'] = 2 ;
        if ($Score[$v]['ScoreBlue']==$Score[$v]['ScoreRed']) $Score[$v]['WhoWin'] = 0;


    }
//echo "<pre>"; print_r($Score); echo "</pre>"; // Счет + раунды

    $PlayerStat = $db->query("SELECT * FROM  PlayerStat WHERE GameListId='$GameId' ORDER BY Score DESC")->FETCHALL(PDO::FETCH_ASSOC);
    $n = 0;
    foreach ($PlayerStat as $row){
        $PlayerId = $row['PlayerId'];
        $PlayerList = $db->query("SELECT * FROM  PlayersList WHERE Name='$PlayerId'")->FETCH(PDO::FETCH_ASSOC);
        $PlayerList_Name =  $PlayerList['Name'];
        $PlayerName = $db->query("SELECT * FROM  PlayerNameList WHERE id='$PlayerList_Name'")->FETCH(PDO::FETCH_ASSOC);
        $ShowName = $PlayerName['PlayerName'];
        $TagerId = $PlayerList['TagerId'];  
        $WeaponList = $db->query("SELECT * FROM   WeaponList WHERE TagerId='$TagerId'")->FETCH(PDO::FETCH_ASSOC);   
        if (empty($PlayerName['PlayerName']))   $ShowName = $WeaponList['TagerId']; 

        $ALL_player[$n]['id']=$PlayerId;
        $ALL_player[$n]['Name']=$ShowName;
        $color = 'Blue';
        if ($PlayerList['Color']==1) $color = 'Red';
        $ALL_player[$n]['Color']=$color;
        $ALL_player[$n]['Weapon']=$WeaponList['Name'];
        $ALL_player[$n]['Score']=$row['Score'];
        $ALL_player[$n]['NTakePoint']=$row['NTakePoint'];
        $ALL_player[$n]['NFirstTake']=$row['NFirstTake'];
        $ALL_player[$n]['NLastTake']=$row['NLastTake'];
        $ALL_player[$n]['NSetupBomb']=$row['NSetupBomb'];
        $ALL_player[$n]['NTakePointFlag']=$row['NTakePointFlag'];
        $n++;
    }
    function nameplayer ($PlayerId, $ALL_player) {


    }
    $victory_null ='&nbsp;';
// =============ЗНАМЕНОСЕЦ==================
    $ScoreMax0 = $db->query("SELECT max(NTakePointFlag) FROM PlayerStat WHERE GameListId='$GameId'")->FETCH(PDO::FETCH_ASSOC);
    $up = $ScoreMax0['max(NTakePointFlag)'];
    $NTakePointFlag = $db->query("SELECT PlayerId FROM PlayerStat WHERE NTakePointFlag = '$up' ")->FETCH(PDO::FETCH_ASSOC);
    $prize['ЗНАМЕНОСЕЦ']['PlayerId'] = $NTakePointFlag['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['ЗНАМЕНОСЕЦ']['PlayerId'] ) {$prize['ЗНАМЕНОСЕЦ']['Name'] =  $row['Name']; continue;   }
    }
    if (empty($prize['ЗНАМЕНОСЕЦ']['Name'])) $prize['ЗНАМЕНОСЕЦ']['Name'] = $victory_null;
    $prize['ЗНАМЕНОСЕЦ']['image']='знаменосец';
// =============ЗНАМЕНОСЕЦ==================
// =============ВЗРЫВНИК==================
    $ScoreMax0 = $db->query("SELECT max(NSetupBomb) FROM PlayerStat WHERE GameListId='$GameId'")->FETCH(PDO::FETCH_ASSOC);
    $up = $ScoreMax0['max(NSetupBomb)'];
    $NSetupBomb = $db->query("SELECT PlayerId FROM PlayerStat WHERE NSetupBomb = '$up' ")->FETCH(PDO::FETCH_ASSOC);
    $prize['ВЗРЫВНИК']['PlayerId'] = $NSetupBomb['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['ВЗРЫВНИК']['PlayerId'] ) {$prize['ВЗРЫВНИК']['Name'] =  $row['Name']; continue;   }
    }
    if (empty($prize['ВЗРЫВНИК']['Name'])) $prize['ВЗРЫВНИК']['Name'] = $victory_null;
    $prize['ВЗРЫВНИК']['image']='взрывник';
// =============ВЗРЫВНИК==================
// =============РАКЕТА==================
    $ScoreMax0 = $db->query("SELECT max(NFirstTake) FROM PlayerStat WHERE GameListId='$GameId'")->FETCH(PDO::FETCH_ASSOC);
    $up = $ScoreMax0['max(NFirstTake)'];
    $NFirstTake = $db->query("SELECT PlayerId FROM PlayerStat WHERE NFirstTake = '$up' ")->FETCH(PDO::FETCH_ASSOC);
    $prize['РАКЕТА']['PlayerId'] = $NFirstTake['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['РАКЕТА']['PlayerId'] ) {$prize['РАКЕТА']['Name'] =  $row['Name']; continue;   }
    }
    if (empty($prize['РАКЕТА']['Name'])) $prize['РАКЕТА']['Name'] = $victory_null;
    $prize['РАКЕТА']['image']='ракета';
// =============РАКЕТА==================
// =============ТИМ ЛИДЕР==================
    $ScoreMax0 = $db->query("SELECT max(Score) FROM PlayerStat WHERE GameListId='$GameId'")->FETCH(PDO::FETCH_ASSOC);
    $up = $ScoreMax0['max(Score)'];
    $ScoreMax = $db->query("SELECT PlayerId FROM PlayerStat WHERE Score = '$up' ")->FETCH(PDO::FETCH_ASSOC);
    $prize['ТИМ ЛИДЕР']['PlayerId'] = $ScoreMax['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['ТИМ ЛИДЕР']['PlayerId'] ) {$prize['ТИМ ЛИДЕР']['Name'] =  $row['Name']; continue; }
    }
    if (empty($prize['ТИМ ЛИДЕР']['Name'])) $prize['ТИМ ЛИДЕР']['Name'] = $victory_null;
    $prize['ТИМ ЛИДЕР']['image']='тим%20лидер';
// =============ТИМ ЛИДЕР==================
// =============ЗАХВАТЧИК==================
    $ScoreMax0 = $db->query("SELECT max(NTakePoint) FROM PlayerStat WHERE GameListId='$GameId'")->FETCH(PDO::FETCH_ASSOC);
    $up = $ScoreMax0['max(NTakePoint)'];
    $NTakePoint = $db->query("SELECT PlayerId FROM PlayerStat WHERE NTakePoint = '$up' ")->FETCH(PDO::FETCH_ASSOC);
    $prize['ЗАХВАТЧИК']['PlayerId'] = $NTakePoint['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['ЗАХВАТЧИК']['PlayerId'] ) {$prize['ЗАХВАТЧИК']['Name'] =  $row['Name']; continue; }
    }
    if (empty($prize['ЗАХВАТЧИК']['Name'])) $prize['ЗАХВАТЧИК']['Name'] = $victory_null;
    $prize['ЗАХВАТЧИК']['image']='захватчик';
// =============ЗАХВАТЧИК==================
// =============РЭМБО==================
    $ScoreMax0 = $db->query("SELECT max(NLastTake) FROM PlayerStat WHERE GameListId='$GameId'")->FETCH(PDO::FETCH_ASSOC);
    $up = $ScoreMax0['max(NLastTake)'];
    $NLastTake = $db->query("SELECT PlayerId FROM PlayerStat WHERE NLastTake = '$up' ")->FETCH(PDO::FETCH_ASSOC);
    $prize['РЭМБО']['PlayerId'] = $NLastTake['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['РЭМБО']['PlayerId'] ) {$prize['РЭМБО']['Name'] =  $row['Name']; continue; }
    }
    if (empty($prize['РЭМБО']['Name'])) $prize['РЭМБО']['Name'] = $victory_null;
    $prize['РЭМБО']['image']='рэмбо';
// =============РЭМБО==================
// =============В ТЫЛУ ВРАГА==================
    $NTakePoint = $db->query("SELECT * FROM PlayerStat WHERE GameListId='$GameId'")->FETCHALL(PDO::FETCH_ASSOC);
    $max=0;
    foreach ($NTakePoint as $row){
        $max2=$row['Score']*$row['NTakePoint'];
        if ($max2>$max) {$max=$max2; $best['PlayerId']=$row['PlayerId'];}   
    }
    $prize['В ТЫЛУ ВРАГА']['PlayerId'] = $best['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['В ТЫЛУ ВРАГА']['PlayerId'] ) {$prize['В ТЫЛУ ВРАГА']['Name'] =  $row['Name']; continue;   }
    }
    if (empty($prize['В ТЫЛУ ВРАГА']['Name'])) $prize['В ТЫЛУ ВРАГА']['Name'] = $victory_null;  
    $prize['В ТЫЛУ ВРАГА']['image']='в%20тылу%20врага';
// =============В ТЫЛУ ВРАГА==================
// =============ВЫЖИВШИЙ==================
    $bigall = $db->query("SELECT * FROM Rounds WHERE GameId='$GameId' ORDER BY id DESC")->FETCHALL(PDO::FETCH_ASSOC);
    for ($v=0; $v<count($bigall); $v++) {
        if ($bigall[$v]['PlayerWinner']<>0) {   $big['PlayerId'] = $bigall[$v]['PlayerWinner']; goto g; }
        else $big['PlayerId'] =0;
    }

    g:
    $prize['ВЫЖИВШИЙ']['PlayerId'] = $big['PlayerId'];
    foreach ($ALL_player as $row){
        if ($row['id'] == $prize['ВЫЖИВШИЙ']['PlayerId'] ) {$prize['ВЫЖИВШИЙ']['Name'] =  $row['Name']; continue;   }
    }
    if (empty($prize['ВЫЖИВШИЙ']['Name'])) $prize['ВЫЖИВШИЙ']['Name'] = $victory_null;
    $prize['ВЫЖИВШИЙ']['image']='выживший';
// =============ВЫЖИВШИЙ==================
    $win['red'] =0;
    $win['blue'] =0;
    ?>


<div class="background-statistic">

    <!--Левая половина экрана-->
    <div class="left-block">


        <!--Блок результаты игры-->
        <div class="results">
            <h1><span class="first">Р</span>ЕЗУЛЬТАТЫ <?php echo $GameList_name; ?></h1>
            <table>

                <!--1 строка таблицы-->
                <tr>
                    <th>№</th>
                    <th>НАЗВАНИЕ РАУНДА</th>
                    <th>РЕЗУЛЬТАТ</th>
                </tr>

                <!--2 строка таблицы-->
                <?php 
                
                for ($v=0; $v<count($Score); $v++) {
                $n=$v+1;
                if ($Score[$v]['WhoWin']==1) {$win['red']++; $victory='ПОБЕДА'; $class='red-text'; $text='КРАСНЫХ'; $result1=$Score[$v]['ScoreRed'];$result2=$Score[$v]['ScoreBlue'];}
                if ($Score[$v]['WhoWin']==2) {$win['blue']++; $victory='ПОБЕДА'; $class='blue-text'; $text='СИНИХ'; $result1=$Score[$v]['ScoreBlue'];$result2=$Score[$v]['ScoreRed'];}
                if ($Score[$v]['WhoWin']==0) {
                    $class='text';  
                    $victory='НИЧЬЯ'; 
                    $text=''; 
                    $victory='НИЧЬЯ'; 
                    $result1=$Score[$v]['ScoreBlue'];
                    $result2=$Score[$v]['ScoreRed'];
                }
                echo "
                 <tr>
                    <td>$n</td>
                    <td>{$Score[$v]['MissionName']}</td>
                    <td>
                    $victory <span class='$class'>$text</span> (<span class='$class'>{$result1}</span>/$result2)</td>
                </tr>
                
                ";
                }
                ?>
            </table>

            <div class="counter">
                <p>СЧЁТ</p>
                <p style="font-size: 60px"><span class="red"><?php echo $win['red']; ?></span> : <span class="blue"><?php echo $win['blue']; ?></span> </p>
            </div>
        </div>
        <!--Конец блока результаты игры-->


        <!--Блок НАГРАДЫ-->
        <div class="rewards">
            <h1><span class="first">Н</span>АГРАДЫ</h1>

            <?php
            $key = array_keys($prize);
            for ($v=0; $v<count($prize); $v++) {
            $imagename = $prize[$key[$v]]['image'];
            
            
if (($v+1)%4===0)
echo "<div class='player margin-0'>";
else 
echo "<div class='player'>";


            echo "
                <div class='border'><img src='/assets/images/$imagename.png'></div>
                <p class='login'>{$prize[$key[$v]]['Name']}</p>
                <span class='text'>{$key[$v]}</span>
            </div>" ;
            }
?> 
            

        </div>
        <!--Конец блока НАГРАДЫ-->


        <!--Блок списка достижений-->
       
        <!--Конец блока списка достижений-->

    </div>
    <!--конец блока Левая половина экрана-->




    <!--Правая половина экрана - ТАБЛИЦА-->
    <div class="right-block">
        <h1><span class="first">С</span>ПИСОК ИГРОКОВ</h1>


        <!--ТАБЛИЦА-->
        <table>
            <!--1 строка таблицы-->
            <tr>
                <th>№</th>
                <th>Имя\ID</th>
                <th>Команда</th>
                <th>Оружие</th>
                <th>Очки</th>
                <th>Кол-во<br>захватов</th>
                <th>Кол-во<br>первых<br>захватов</th>
                <th>Кол-во<br>последних<br>захватов</th>
                <th>Установлено<br>бомб</th>
                <th>Перенесено<br>флагов</th>
            </tr>

            <!--2 строка таблицы-->
            <?php
            for ($v=0; $v<count($ALL_player); $v++) {
            $n = $v+1;
            for ($d=0; $d<count($prize); $d++) {
            if ($prize[$key[$d]]['PlayerId']==$ALL_player[$v]['id']){ $PlayerPrize=$key[$d]; goto a;}
            else {$PlayerPrize = 'Нет награды';}
            }
            a:
            $text="<span class='blue-text'>Синяя</span>";
            if ($ALL_player[$v]['Color']=='Red') $text="<span class='red-text'>Красная</span>";
            echo "
            <tr>
                <td>$n</td>
                <td>{$ALL_player[$v]['Name']}</td>
                <td>$text</td>
                <td>{$ALL_player[$v]['Weapon']}</td>
                <td>{$ALL_player[$v]['Score']}</td>
               <td>{$ALL_player[$v]['NTakePoint']}</td>
                <td>{$ALL_player[$v]['NFirstTake']}</td>
                <td>{$ALL_player[$v]['NLastTake']}</td>
                <td>{$ALL_player[$v]['NSetupBomb']}</td>
               <td>{$ALL_player[$v]['NTakePointFlag']}</td>
            </tr>";
            }
?>
           

        </table>
        <!--КОНЕЦ ТАБЛИЦЫ-->
    </div>
    <!--конец блока Правая половина экрана - ТАБЛИЦА-->

</div>
</main>
</body>
</html>