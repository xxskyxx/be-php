<?php

/**
 * Post
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Post extends BasePost
{
  const LAST_COMMENTS_MAX = 5;  
  
  /**
   * Возвращает указанное число наиболее поздних комментариев
   * 
   * @param   integer               $count  числовыбираемых комментариев
   * 
   * @return  Doctrine_Collection   
   */
  public function getLastComments($count = 0)
  {
    if ($count == 0)
    {
      return $this->getCommentsSlice(0);
    }
    else
    {
      $from = $this->comments->count() - $count;
      $from = ($from < 0) ? 0 : $from;
      return $this->getCommentsSlice($from, $count);
    }
  }
  
  /**
   * Возвращает окно из полного списка комментариев
   * 
   * @param   integer               $from   Начало окна выборки
   * @param   integer               $count  Размер окна выборки
   * 
   * @return  Doctrine_Collection   
   */
  protected function getCommentsSlice($from, $count = 0)
  {
    if ($count > 0)
    {
      return Doctrine::getTable('Comment')
          ->createQuery('c')->leftJoin('c.Post')->leftJoin('c.WebUser')
          ->select()->where('post_id = ?', $this->id)->orderBy('create_time')
          ->offset($from)->limit($count)
          ->execute();
    }
    else
    {
      return Doctrine::getTable('Comment')
          ->createQuery('c')->leftJoin('c.Post')->leftJoin('c.WebUser')
          ->select()->where('post_id = ?', $this->id)->orderBy('create_time ASC')
          ->execute();
    }
  }

}