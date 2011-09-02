<?php

/**
 * Comment form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentForm extends BaseCommentForm
{
  public function configure()
  {
    //Блог будет задаваться принудительно.
    unset($this['post_id']);
    $this->setWidget('post_id', new sfWidgetFormInputHidden());
    $this->setValidator('post_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Post'))));
    //Автор будет задаваться принудительно.
    unset($this['web_user_id']);
    $this->setWidget('web_user_id', new sfWidgetFormInputHidden());
    $this->setValidator('web_user_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'))));
    //Дата ставится автоматически.
    unset($this['create_time']);

    $this->setWidget('ret_url', new sfWidgetFormInputHidden());
    $this->setValidator('ret_url', new sfValidatorString(array('required' => false)));
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', false);

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'text' => 'Комментарий:'
    ));

    $this->getWidgetSchema()->setHelps(array(
        'text' => 'Разрешен BBCode.'
    ));
  }
}
