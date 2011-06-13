<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title><?php echo $game->name ?></title>
    <meta http-equiv="refresh" content="<?php echo $game->update_interval ?>; url=<?php echo url_for('gameStats/autoUpdate?id='.$game->id) ?>" />
  </head>
  <body>
    <div>
      <?php echo $game->name.' - '.Timing::timeToStr($game->game_last_update) ?>
    </div>
    <div>
      <?php echo $result ?>
    </div>
  </body>
</html>