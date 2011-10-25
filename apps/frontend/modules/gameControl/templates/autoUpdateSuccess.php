<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title><?php echo $_game->name ?></title>
    <meta http-equiv="refresh" content="<?php echo $_game->update_interval ?>; url=<?php echo url_for('gameControl/autoUpdate?id='.$_game->id) ?>" />
  </head>
  <body>
    <div><?php echo $_game->name.' - '.Timing::timeToStr($_game->game_last_update) ?></div>
    <div><?php echo $_result ?></div>
  </body>
</html>