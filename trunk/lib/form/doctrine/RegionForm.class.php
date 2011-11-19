<?php

/**
 * Region form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RegionForm extends BaseRegionForm
{
  public function configure()
  {
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название:'
    ));
    $this->getWidgetSchema()->setHelps(array(
        'name' => 'Лучше более краткое.'
    ));
  }
}
