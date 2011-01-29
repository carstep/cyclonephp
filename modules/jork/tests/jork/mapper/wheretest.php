<?php


class JORK_Mapper_WhereTest extends Kohana_Unittest_TestCase {

    public function testWhereImpl() {
        $jork_query = JORK::from('Model_User')
                ->where('posts.created_at', '>', DB::expr('2010-11-11'))
                ->where('exists', 'name')
                ->where('avg({id}) > x');
        $mapper = new JORK_Mapper_Select($jork_query);
        list($db_query, ) = $mapper->map();
        $this->assertEquals($db_query->where_conditions, array(
            new DB_Expression_Binary('t_posts_0.created_at', '>'
                    , DB::expr('2010-11-11')),
            new DB_Expression_Unary('exists', 't_users_0.name'),
            new DB_Expression_Custom('avg(t_users_0.id) > x')
        ));
    }

    public function testWhere() {
        $jork_query = JORK::from('Model_User user')
                ->where('user.posts.created_at', '>', DB::expr('2010-11-11'))
                ->where('exists', 'user.name')
                ->where('avg({user.id}) > x');
        $mapper = new JORK_Mapper_Select($jork_query);
        list($db_query, ) = $mapper->map();
        $this->assertEquals($db_query->tables, array(
            array('t_users', 't_users_0')
        ));
        $this->assertEquals($db_query->joins, array(
            array(
                'table' => array('user_contact_info', 'user_contact_info_0'),
                'type' => 'LEFT',
                'conditions' => array(
                    new DB_Expression_Binary('t_users_0.id', '=', 'user_contact_info_0.user_fk')
                )
            ),
            array(
                'table' => array('t_posts', 't_posts_0'),
                'type' => 'LEFT',
                'conditions' => array(
                    new DB_Expression_Binary('t_users_0.id', '=', 't_posts_0.user_fk')
                )
            )
        ));
        $this->assertEquals($db_query->where_conditions, array(
            new DB_Expression_Binary('t_posts_0.created_at', '>', DB::expr('2010-11-11')),
            new DB_Expression_Unary('exists', 't_users_0.name'),
            new DB_Expression_Custom('avg(t_users_0.id) > x')
        ));
    }

    public function testWhereObj() {
        $jork_query = JORK::from('Model_Post post')
            ->where('post.author', '=', 'post.topic.creator');
        $mapper = new JORK_Mapper_Select($jork_query);
        list($db_query, ) = $mapper->map();
        $this->assertEquals($db_query->tables, array(
            array('t_posts', 't_posts_0')
        ));
        $this->assertEquals($db_query->where_conditions, array(
            new DB_Expression_Binary('t_users_0.id', '=', 't_users_1.id')
        ));
        $this->assertEquals($db_query->joins, array(
           array(
               'table' => array('t_users', 't_users_0'),
               'type' => 'LEFT',
               'conditions' => array(
                   new DB_Expression_Binary('t_posts_0.user_fk', '=', 't_users_0.id')
               )
           ),
           array(
               'table' => array('user_contact_info', 'user_contact_info_0'),
               'type' => 'LEFT',
               'conditions' => array(
                   new DB_Expression_Binary('t_users_0.id', '=', 'user_contact_info_0.user_fk')
               )
           ),
           array(
               'table' => array('t_topics', 't_topics_0'),
               'type' => 'LEFT',
               'conditions' => array(
                   new DB_Expression_Binary('t_posts_0.topic_fk', '=', 't_topics_0.id')
               )
           ),
           array(
               'table' => array('t_users', 't_users_1'),
               'type' => 'LEFT',
               'conditions' => array(
                   new DB_Expression_Binary('t_topics_0.creator_fk', '=', 't_users_1.id')
               )
           ),
           array(
               'table' => array('user_contact_info', 'user_contact_info_1'),
               'type' => 'LEFT',
               'conditions' => array(
                   new DB_Expression_Binary('t_users_1.id', '=', 'user_contact_info_1.user_fk')
               )
           )
        ));
    }

    /**
     * @expectedException JORK_Exception
     * @expectedExceptionMessage unable to check equality of class 'Model_User' with class 'Model_Topic'
     */
    public function testWhereObjFailOnClassDifference() {
        $jork_query = JORK::from('Model_Post post')
            ->where('post.author', '=', 'post.topic');
        $mapper = new JORK_Mapper_Select($jork_query);
        $mapper->map();
    }
}