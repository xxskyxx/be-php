<?php

/**
 * BaseTask
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $time_per_task_local
 * @property boolean $manual_start
 * @property integer $try_count_local
 * @property integer $priority_free
 * @property integer $priority_queued
 * @property integer $priority_busy
 * @property integer $priority_filled
 * @property integer $priority_per_team
 * @property integer $max_teams
 * @property boolean $locked
 * @property integer $game_id
 * @property Game $Game
 * @property Doctrine_Collection $answers
 * @property Doctrine_Collection $taskConstraints
 * @property Doctrine_Collection $taskStates
 * @property Doctrine_Collection $TeamState
 * @property Doctrine_Collection $tips
 * 
 * @method integer             getId()                  Returns the current record's "id" value
 * @method string              getName()                Returns the current record's "name" value
 * @method integer             getTimePerTaskLocal()    Returns the current record's "time_per_task_local" value
 * @method boolean             getManualStart()         Returns the current record's "manual_start" value
 * @method integer             getTryCountLocal()       Returns the current record's "try_count_local" value
 * @method integer             getPriorityFree()        Returns the current record's "priority_free" value
 * @method integer             getPriorityQueued()      Returns the current record's "priority_queued" value
 * @method integer             getPriorityBusy()        Returns the current record's "priority_busy" value
 * @method integer             getPriorityFilled()      Returns the current record's "priority_filled" value
 * @method integer             getPriorityPerTeam()     Returns the current record's "priority_per_team" value
 * @method integer             getMaxTeams()            Returns the current record's "max_teams" value
 * @method boolean             getLocked()              Returns the current record's "locked" value
 * @method integer             getGameId()              Returns the current record's "game_id" value
 * @method Game                getGame()                Returns the current record's "Game" value
 * @method Doctrine_Collection getAnswers()             Returns the current record's "answers" collection
 * @method Doctrine_Collection getTaskConstraints()     Returns the current record's "taskConstraints" collection
 * @method Doctrine_Collection getTaskStates()          Returns the current record's "taskStates" collection
 * @method Doctrine_Collection getTeamState()           Returns the current record's "TeamState" collection
 * @method Doctrine_Collection getTips()                Returns the current record's "tips" collection
 * @method Task                setId()                  Sets the current record's "id" value
 * @method Task                setName()                Sets the current record's "name" value
 * @method Task                setTimePerTaskLocal()    Sets the current record's "time_per_task_local" value
 * @method Task                setManualStart()         Sets the current record's "manual_start" value
 * @method Task                setTryCountLocal()       Sets the current record's "try_count_local" value
 * @method Task                setPriorityFree()        Sets the current record's "priority_free" value
 * @method Task                setPriorityQueued()      Sets the current record's "priority_queued" value
 * @method Task                setPriorityBusy()        Sets the current record's "priority_busy" value
 * @method Task                setPriorityFilled()      Sets the current record's "priority_filled" value
 * @method Task                setPriorityPerTeam()     Sets the current record's "priority_per_team" value
 * @method Task                setMaxTeams()            Sets the current record's "max_teams" value
 * @method Task                setLocked()              Sets the current record's "locked" value
 * @method Task                setGameId()              Sets the current record's "game_id" value
 * @method Task                setGame()                Sets the current record's "Game" value
 * @method Task                setAnswers()             Sets the current record's "answers" collection
 * @method Task                setTaskConstraints()     Sets the current record's "taskConstraints" collection
 * @method Task                setTaskStates()          Sets the current record's "taskStates" collection
 * @method Task                setTeamState()           Sets the current record's "TeamState" collection
 * @method Task                setTips()                Sets the current record's "tips" collection
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTask extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('tasks');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 32, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 32,
             ));
        $this->hasColumn('time_per_task_local', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('manual_start', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('try_count_local', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('priority_free', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('priority_queued', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => -10,
             ));
        $this->hasColumn('priority_busy', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('priority_filled', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => -500,
             ));
        $this->hasColumn('priority_per_team', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => -10,
             ));
        $this->hasColumn('max_teams', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('locked', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('game_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Game', array(
             'local' => 'game_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('Answer as answers', array(
             'local' => 'id',
             'foreign' => 'task_id'));

        $this->hasMany('TaskConstraint as taskConstraints', array(
             'local' => 'id',
             'foreign' => 'task_id'));

        $this->hasMany('TaskState as taskStates', array(
             'local' => 'id',
             'foreign' => 'task_id'));

        $this->hasMany('TeamState', array(
             'local' => 'id',
             'foreign' => 'task_id'));

        $this->hasMany('Tip as tips', array(
             'local' => 'id',
             'foreign' => 'task_id'));
    }
}