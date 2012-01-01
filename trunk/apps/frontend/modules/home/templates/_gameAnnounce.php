<?php
/* Входные параметры:
 * - Game $game - игра
 * - boolean $_isAuth - авторизован ли пользователь.
 * - boolean $_showRegions - показывать или нет регионы
 */
  $name = $_isAuth ? link_to($game->name, 'game/show?id='.$game->id) : $game->name;
  $formatedName = '<div><span class="info" style="font-weight:bold">'.$name.'</span></div>';
  $date = '<div>'.$game->start_datetime.'</div>';
  $region = $_showRegions ? '<div>'.$game->getRegionSafe()->name.'</div>' : '';
  $info = '<div>'.$game->short_info.'</div>';
  echo decorate_div('namedLineBox', $formatedName.$region.$date.$info);
?>
