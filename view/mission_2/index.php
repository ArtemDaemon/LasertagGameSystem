<?php
use classes\BaseHelper;
?>
<div class="b-h1">Игра <?= $game->name() ?></div>

<div class="b-game__box">
    <div class="b-h2">Выбор сценария</div>

    <form action="/">
        <input type="hidden" name="command" value="missionInit">
        <input type="hidden" name="view" value="base-ready-wait">
        <div>
            <select name="missionId">
                <?php foreach($missions as $item): ?>
                    <option value="<?= $item['id'] ?>" <?= ($item['id']==$mission->id) ? 'selected' : false ?>><?= $item['Name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <input type="hidden" id="anyId" name="anyId" value="1">
            <span>Список id кто может захватить точку:</span> <span><label><input type="checkbox" onchange="anyIdToggle()" checked> Любой игрок</label></span>

            <div>
                <select name="id1">
                    <option value="0">Не выбран</option>
                    <?php foreach($players as $player): ?>
                        <option value="<?= $player['TagerId'] ?>"><?= $player['TagerId'] . ': ' . $player['PlayerName'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div>
                <select name="id2">
                    <option value="0">Не выбран</option>
                    <?php foreach($players as $player): ?>
                        <option value="<?= $player['TagerId'] ?>"><?= $player['TagerId'] . ': ' . $player['PlayerName'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div>
                <select name="id3">
                    <option value="0">Не выбран</option>
                    <?php foreach($players as $player): ?>
                        <option value="<?= $player['TagerId'] ?>"><?= $player['TagerId'] . ': ' . $player['PlayerName'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <button class="b-btn b-btn_grey js-reloader_off">Начать раунд</button>
    </form>
</div>

<div class="b-gamesettings">
    <div class="g-text-center g-bold g-m20">Настройки раунда</div>

    <div>Режим точек: Перестрелка по очереди</div>
    <div>Время раунда: <?= date("i:s", $mission->getMaxTime()) ?></div>
    <div>Очков для победы: <?= $mission->getMaxScore() ?></div>
    <div class="g-m-t5">Время сброса захвата: <?= $mission->timeResetTogether() ?></div>
</div>

<div class="b-table__wrap">
    <div class="g-text-center g-bold g-m20">Состояние точек</div>

    <table>
        <thead>
        <tr>
            <th>№</th>
            <th>Название точки</th>
            <th>Приоритет</th>
            <th>Накоплено времени</th>
            <th>Статус</th>
            <th>Захватов Красными</th>
            <th>Захватов Синими</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach($points as $point): ?>
            <?php
            if(!$pointSetup = $mission->getPointSetup($point['id'])) {
                $point['status'] = \entities\Point::STATUS_NOT_PLAY;
            }
            ?>
            <tr>
                <td><?= $point['id'] ?></td>
                <td><?= $point['Name'] ?></td>
                <td><?= $mission->getPriority($point['id'])?></td>
                <td><?= $point['time'] ?></td>
                <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_1) ?></td>
                <td><?= $point['NRedTake'] ?></td>
                <td><?= $point['NBlueTake'] ?></td>
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
            <div>Режим работы: <?= BaseHelper::typeWork($bases['redType']) ?></div>
            <?= BaseHelper::getAdditional($game, $mission, $bases['red'], $bases['redType']) ?>
            <div><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBaseRed']) ?></div>
        </div>
        <div class="grid__col-1-2">
            <div>
                <span class="g-text-center g-bold">База синих (<?= $bases['blue']->name ?>)</span>
                <?= BaseHelper::statusLabel($bases['blue']->getStatus()) ?>
            </div>
            <div>Режим работы: <?= BaseHelper::typeWork($bases['blueType']) ?></div>
            <?= BaseHelper::getAdditional($game, $mission, $bases['blue'], $bases['blueType']) ?>
            <div><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBaseBlue']) ?></div>
        </div>
    </div>
</div>