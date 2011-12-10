<?php

/**
 * Article form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ArticleForm extends BaseArticleForm
{
  public function configure()
  {
    unset($this['name']);
    $this->setWidget('name', new sfWidgetFormInputText());
    $this->setValidator('name', new sfValidatorString(array('max_length' => 256, 'required' => true)));
    
    unset($this['path']);
    $this->setWidget('path', new sfWidgetFormInputText());
    $this->setValidator('path', new sfValidatorString(array('max_length' => 1024, 'required' => false)));
    
    unset($this['created_at']);
    unset($this['web_user_id']);
    
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название:',
        'path' => 'Путь:',
        'text' => 'Текст:'
    ));

    $this->getWidgetSchema()->setHelps(array(
        'name' => 'Краткость - сестра таланта! <span class="warn">Желательно без пробелов</span>.',
        'path' => 'Названия статей в порядке углубления в иерархию, <span class="warn">разделенные знаком "\"</span>.',
        'text' => Article::BBCODE_HELP_LINK
    ));
    
  }
}
