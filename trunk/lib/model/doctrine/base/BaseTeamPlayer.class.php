<?php

/**
 * BaseTeamPlayer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $web_user_id
 * @property integer $team_id
 * @property boolean $is_leader
 * @property WebUser $WebUser
 * @property Team $Team
 * 
 * @method integer    getId()          Returns the current record's "id" value
 * @method integer    getWebUserId()   Returns the current record's "web_user_id" value
 * @method integer    getTeamId()      Returns the current record's "team_id" value
 * @method boolean    getIsLeader()    Returns the current record's "is_leader" value
 * @method WebUser    getWebUser()     Returns the current record's "WebUser" value
 * @method Team       getTeam()        Returns the current record's "Team" value
 * @method TeamPlayer setId()          Sets the current record's "id" value
 * @method TeamPlayer setWebUserId()   Sets the current record's "web_user_id" value
 * @method TeamPlayer setTeamId()      Sets the current record's "team_id" value
 * @method TeamPlayer setIsLeader()    Sets the current record's "is_leader" value
 * @method TeamPlayer setWebUser()     Sets the current record's "WebUser" value
 * @method TeamPlayer setTeam()        Sets the current record's "Team" value
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTeamPlayer extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('team_players');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('web_user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('team_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('is_leader', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));


        $this->index('ui_webuser_team', array(
             'type' => 'unique',
             'fields' => 
             array(
              0 => 'web_user_id',
              1 => 'team_id',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('WebUser', array(
             'local' => 'web_user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Team', array(
             'local' => 'team_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}