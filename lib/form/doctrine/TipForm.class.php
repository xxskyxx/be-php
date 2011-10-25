<?php

/**
 * Tip form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TipForm extends BaseTipForm
{
  public function configure()
  {
    //Задание будет устанавливаться принудительно.
    unset($this['task_id']);
    $this->setWidget('task_id', new sfWidgetFormInputHidden());
    $this->setValidator('task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));

    //Код можно выбирать только от текущего задания.
    $query = Doctrine_Core::getTable('Answer')
        ->createQuery('a')
        ->select()
        ->where('task_id = ?', array($this->getObject()->task_id));
    $this->setWidget('answer_id', new sfWidgetFormDoctrineChoice(array('model' => 'Answer', 'add_empty' => '(нет)', 'method' => 'getName', 'query' => $query)));
    $this->setValidator('answer_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Answer'), 'required' => false)));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название:',
        'define' => 'Формулировка:',
        'delay' => 'Задержка выдачи:',
        'answer_id' => 'Выдавать после ответа:'
    ));

    $this->getWidgetSchema()->setHelps(array(
        'name' => 'На игре известно только организаторам.',
        'define' => 'Разрешен BBCode.',
        'delay' => 'мин.|<span class="info">Если пусто или "0", а подсказка только что создана, то будет взято значение из свойств игры, умноженное на число уже имеющихся подсказок.</span>',
        'answer_id' => 'Подсказка выдается сразу после ввода этого ответа и только в таком случае, а не по задержке.'
    ));
  }
}
