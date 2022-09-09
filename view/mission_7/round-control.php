<?php
use classes\BaseHelper;
?>

<div class="b-h1">Игра <?= $game->name() ?></div>

<div class="b-game__box">
    <div class="b-h2">Идет игра</div>

    <div class="g-clearfix grid__row">
        <div class="grid__col-1-2">
            <div>
                <div><span class="g-bold">Сценарий:</span> <?= $mission->getName() ?></div>
                <div class="js-reload__round-time">Время раунда: <?= date("i:s", $game->getRoundTime($mission->getMaxTime())) ?></div>
            </div>
        </div>
        <div class="grid__col-1-2">
            <div>
                <div><span class="g-bold">Набранные очки</div>
                <div>
                    <table class="js-reload__score-table b-table__scores">
                        <tr>
                            <td>Красные: <?= $game->getRedScore() ?></td>
                            <td>Синие: <?= $game->getBlueScore() ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php if(!$round): ?>
            <div class="js-reload__ajax-reloader"></div>
        <?php endif ?>
    </div>

    <div class="g-clearfix grid__row">
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="forceRoundEnd" value="true">
                <button class="b-btn b-btn_grey js-reloader_off">Прервать раунд</button>
            </form>
        </div>
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="whoWin" value="<?= RED_TEAM ?>">
                <button class="b-btn b-btn_grey js-reloader_off">Победа красных</button>
            </form>
        </div>
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="whoWin" value="<?= DRAW_GAME ?>">
                <button class="b-btn b-btn_grey js-reloader_off">Ничья</button>
            </form>
        </div>
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="whoWin" value="<?= BLUE_TEAM ?>">
                <button class="b-btn b-btn_grey js-reloader_off">Победа синих</button>
            </form>
        </div>
    </div>
</div>

<div class="b-bomblist js-bomblist">
    <div class="g-bold g-text-center">Список кто несет бомбу</div>
    <?php
    $playersWithBomb = [];
    foreach($game->getPlayersWithBomb() as $i => $item) {
        $pInfo = $game->getPlayerByTager($item['tagerId']);

        $playersWithBomb[$i]['colorId'] = $pInfo['Color'];
        $playersWithBomb[$i]['tagerId'] = $item['tagerId'];
        $playersWithBomb[$i]['time'] = $mission->getBombTimeForPlant() - (time() - strtotime($item['add_date']));
    }
    ?>

    <div class="g-clearfix grid__row">
        <div class="grid__col-1-2">
            <div class="g-m20">
                <div class="g-bold">Красные</div>
                <select multiple size="5" disabled>
                    <?php foreach($playersWithBomb as $item): ?>
                        <?php if($item['colorId'] == RED_TEAM): ?>
                            <option>id <?= $item['tagerId'] ?> | <?= date("i:s", $item['time']) ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="grid__col-1-2">
            <div class="g-m20">
                <div class="g-bold">Синие</div>
                <select multiple size="5" disabled>
                    <?php foreach($playersWithBomb as $item): ?>
                        <?php if($item['colorId'] == BLUE_TEAM): ?>
                            <option>id <?= $item['tagerId'] ?> | <?= date("i:s", $item['time']) ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>

    <div>Максимум 3 бомбы одновременно на каждую команду</div>
</div>

<div class="b-table__wrap">
    <div class="g-text-center g-bold g-m20">Состояние точек</div>

    <table>
        <thead>
        <tr>
            <th>№</th>
            <th>Название точки</th>
            <th>Тип</th>
            <th>Статус</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach($points as $point): ?>
            <?php $pointSetup = $mission->getPointSetup($point['id']) ?>
            <?php if(!$pointSetup): ?>
                <?php $point['status'] = \entities\Point::STATUS_NOT_PLAY ?>
            <?php endif ?>
            <tr>
                <td><?= $point['id'] ?></td>
                <td><?= $point['Name'] ?></td>
                <td><?= ($pointSetup) ? \classes\PointHelper::typeBomb($pointSetup['TypeBomb']) : '-' ?></td>
                <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_6) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<div>
    <div class="g-text-center g-bold g-m20">Режим работы баз</div>
    <div class="g-clearfix grid__row js-reload__bases">
        <div class="grid__col-1-2">
            <div>
                <span class="g-text-center g-bold">База красных (<?= $bases['red']->name ?>)</span>
                <?= BaseHelper::statusLabel($bases['red']->getStatus()) ?>
            </div>
            <div>Режим работы: <?= BaseHelper::typeWork($bases['red']->getType()) ?></div>
            <?= BaseHelper::getAdditional($game, $mission, $bases['red'], $bases['red']->getType()) ?>
            <div><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBaseRed']) ?></div>
        </div>
        <div class="grid__col-1-2">
            <div>
                <span class="g-text-center g-bold">База синих (<?= $bases['blue']->name ?>)</span>
                <?= BaseHelper::statusLabel($bases['blue']->getStatus()) ?>
            </div>
            <div>Режим работы: <?= BaseHelper::typeWork($bases['blue']->getType()) ?></div>
            <?= BaseHelper::getAdditional($game, $mission, $bases['blue'], $bases['blue']->getType()) ?>
            <div><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBaseBlue']) ?></div>
        </div>
    </div>
</div>