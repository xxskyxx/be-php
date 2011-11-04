<?php

/**
 * BaseBlog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property Doctrine_Collection $posts
 * 
 * @method integer             getId()    Returns the current record's "id" value
 * @method Doctrine_Collection getPosts() Returns the current record's "posts" collection
 * @method Blog                setId()    Sets the current record's "id" value
 * @method Blog                setPosts() Sets the current record's "posts" collection
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBlog extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('blogs');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Post as posts', array(
             'local' => 'id',
             'foreign' => 'blog_id'));
    }
}