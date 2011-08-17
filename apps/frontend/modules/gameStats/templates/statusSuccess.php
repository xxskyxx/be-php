<?php
$sessionIsManager = $game->canBeManaged($sf_user->getSessionWebUser()->getRawValue());
$backLinkRaw = 'gameStats/status?id='.$game->id.'&seat='.$seat;
$backLinkEncoded = Utils::encodeSafeUrl(url_for($backLinkRaw));

render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, 'game/show?id='.$game->id),
    'Состояние'
))
?>

<h2>Управление игрой <?php echo $game->name ?></h2>
<div><?php include_partial('StatusHeader', array('game' => $game, 'backLinkEncoded' => $backLinkEncoded, 'sessionIsManager' => $sessionIsManager)); ?></div>
<?php if(!$sessionIsManager): ?>
<div class="warn">Вы можете только просматривать состояние игры, так как не являетесь ее руководителем.</div>
<?php endif; ?>
<?php include_partial('StatusTabs', array('game' => $game, 'backLinkEncoded' => $backLinkEncoded, 'sessionIsManager' => $sessionIsManager, 'seat' => $seat)); ?>
