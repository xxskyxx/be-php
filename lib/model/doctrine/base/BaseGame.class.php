<?php

/**
 * BaseGame
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $short_info
 * @property boolean $short_info_enabled
 * @property clob $description
 * @property integer $team_id
 * @property string $team_name_backup
 * @property integer $region_id
 * @property datetime $start_briefing_datetime
 * @property datetime $start_datetime
 * @property datetime $stop_datetime
 * @property datetime $finish_briefing_datetime
 * @property integer $time_per_game
 * @property integer $time_per_task
 * @property integer $time_per_tip
 * @property integer $try_count
 * @property integer $update_interval
 * @property boolean $teams_can_update
 * @property integer $update_interval_max
 * @property string $task_define_default_name
 * @property string $task_tip_prefix
 * @property integer $status
 * @property integer $started_at
 * @property integer $finished_at
 * @property integer $game_last_update
 * @property Team $Team
 * @property Region $Region
 * @property Doctrine_Collection $gameCandidates
 * @property Doctrine_Collection $tasks
 * @property Doctrine_Collection $teamStates
 * 
 * @method integer             getId()                       Returns the current record's "id" value
 * @method string              getName()                     Returns the current record's "name" value
 * @method string              getShortInfo()                Returns the current record's "short_info" value
 * @method boolean             getShortInfoEnabled()         Returns the current record's "short_info_enabled" value
 * @method clob                getDescription()              Returns the current record's "description" value
 * @method integer             getTeamId()                   Returns the current record's "team_id" value
 * @method string              getTeamNameBackup()           Returns the current record's "team_name_backup" value
 * @method integer             getRegionId()                 Returns the current record's "region_id" value
 * @method datetime            getStartBriefingDatetime()    Returns the current record's "start_briefing_datetime" value
 * @method datetime            getStartDatetime()            Returns the current record's "start_datetime" value
 * @method datetime            getStopDatetime()             Returns the current record's "stop_datetime" value
 * @method datetime            getFinishBriefingDatetime()   Returns the current record's "finish_briefing_datetime" value
 * @method integer             getTimePerGame()              Returns the current record's "time_per_game" value
 * @method integer             getTimePerTask()              Returns the current record's "time_per_task" value
 * @method integer             getTimePerTip()               Returns the current record's "time_per_tip" value
 * @method integer             getTryCount()                 Returns the current record's "try_count" value
 * @method integer             getUpdateInterval()           Returns the current record's "update_interval" value
 * @method boolean             getTeamsCanUpdate()           Returns the current record's "teams_can_update" value
 * @method integer             getUpdateIntervalMax()        Returns the current record's "update_interval_max" value
 * @method string              getTaskDefineDefaultName()    Returns the current record's "task_define_default_name" value
 * @method string              getTaskTipPrefix()            Returns the current record's "task_tip_prefix" value
 * @method integer             getStatus()                   Returns the current record's "status" value
 * @method integer             getStartedAt()                Returns the current record's "started_at" value
 * @method integer             getFinishedAt()               Returns the current record's "finished_at" value
 * @method integer             getGameLastUpdate()           Returns the current record's "game_last_update" value
 * @method Team                getTeam()                     Returns the current record's "Team" value
 * @method Region              getRegion()                   Returns the current record's "Region" value
 * @method Doctrine_Collection getGameCandidates()           Returns the current record's "gameCandidates" collection
 * @method Doctrine_Collection getTasks()                    Returns the current record's "tasks" collection
 * @method Doctrine_Collection getTeamStates()               Returns the current record's "teamStates" collection
 * @method Game                setId()                       Sets the current record's "id" value
 * @method Game                setName()                     Sets the current record's "name" value
 * @method Game                setShortInfo()                Sets the current record's "short_info" value
 * @method Game                setShortInfoEnabled()         Sets the current record's "short_info_enabled" value
 * @method Game                setDescription()              Sets the current record's "description" value
 * @method Game                setTeamId()                   Sets the current record's "team_id" value
 * @method Game                setTeamNameBackup()           Sets the current record's "team_name_backup" value
 * @method Game                setRegionId()                 Sets the current record's "region_id" value
 * @method Game                setStartBriefingDatetime()    Sets the current record's "start_briefing_datetime" value
 * @method Game                setStartDatetime()            Sets the current record's "start_datetime" value
 * @method Game                setStopDatetime()             Sets the current record's "stop_datetime" value
 * @method Game                setFinishBriefingDatetime()   Sets the current record's "finish_briefing_datetime" value
 * @method Game                setTimePerGame()              Sets the current record's "time_per_game" value
 * @method Game                setTimePerTask()              Sets the current record's "time_per_task" value
 * @method Game                setTimePerTip()               Sets the current record's "time_per_tip" value
 * @method Game                setTryCount()                 Sets the current record's "try_count" value
 * @method Game                setUpdateInterval()           Sets the current record's "update_interval" value
 * @method Game                setTeamsCanUpdate()           Sets the current record's "teams_can_update" value
 * @method Game                setUpdateIntervalMax()        Sets the current record's "update_interval_max" value
 * @method Game                setTaskDefineDefaultName()    Sets the current record's "task_define_default_name" value
 * @method Game                setTaskTipPrefix()            Sets the current record's "task_tip_prefix" value
 * @method Game                setStatus()                   Sets the current record's "status" value
 * @method Game                setStartedAt()                Sets the current record's "started_at" value
 * @method Game                setFinishedAt()               Sets the current record's "finished_at" value
 * @method Game                setGameLastUpdate()           Sets the current record's "game_last_update" value
 * @method Game                setTeam()                     Sets the current record's "Team" value
 * @method Game                setRegion()                   Sets the current record's "Region" value
 * @method Game                setGameCandidates()           Sets the current record's "gameCandidates" collection
 * @method Game                setTasks()                    Sets the current record's "tasks" collection
 * @method Game                setTeamStates()               Sets the current record's "teamStates" collection
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGame extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('games');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 16, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 16,
             ));
        $this->hasColumn('short_info', 'string', 2048, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 2048,
             ));
        $this->hasColumn('short_info_enabled', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('description', 'clob', null, array(
             'type' => 'clob',
             'notnull' => true,
             ));
        $this->hasColumn('team_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('team_name_backup', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('region_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('start_briefing_datetime', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('start_datetime', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('stop_datetime', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('finish_briefing_datetime', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('time_per_game', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 540,
             ));
        $this->hasColumn('time_per_task', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 90,
             ));
        $this->hasColumn('time_per_tip', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 30,
             ));
        $this->hasColumn('try_count', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 10,
             ));
        $this->hasColumn('update_interval', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 5,
             ));
        $this->hasColumn('teams_can_update', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('update_interval_max', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 600,
             ));
        $this->hasColumn('task_define_default_name', 'string', 32, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'Загадка',
             'length' => 32,
             ));
        $this->hasColumn('task_tip_prefix', 'string', 32, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'Подсказка',
             'length' => 32,
             ));
        $this->hasColumn('status', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('started_at', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('finished_at', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('game_last_update', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Team', array(
             'local' => 'team_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('Region', array(
             'local' => 'region_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('GameCandidate as gameCandidates', array(
             'local' => 'id',
             'foreign' => 'game_id'));

        $this->hasMany('Task as tasks', array(
             'local' => 'id',
             'foreign' => 'game_id'));

        $this->hasMany('TeamState as teamStates', array(
             'local' => 'id',
             'foreign' => 'game_id'));
    }
}