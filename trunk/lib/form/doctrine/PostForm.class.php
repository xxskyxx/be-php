<?php

/**
 * Post form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PostForm extends BasePostForm
{

  /** Ссылка для возврата на предыдущую страницу, кодированная
   * @var string
   */
  public $retUrlRaw;

  public function configure()
  {
    //Блог будет задаваться принудительно.
    unset($this['blog_id']);
    $this->setWidget('blog_id', new sfWidgetFormInputHidden());
    $this->setValidator('blog_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Blog'))));
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
        'text' => 'Сообщение:'
    ));

    $this->getWidgetSchema()->setHelps(array(
        'text' => 'Разрешен BBCode.'
    ));

  }

}
