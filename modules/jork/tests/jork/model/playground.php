<?php

/**
 * This test class is a general test class used for general functional testing
 */
class JORK_Model_Playground extends JORK_DbTest {

    public function testIfJORKCanSaveTheWorld() {
        $user = new Model_User;
        $user->name = 'newbie01';

        $topic = new Model_Topic;
        $topic->name = 'newbie question - PLEASE HELP';

        $run = 0;
        for ($i = 0; $i < 6; ++$i) { ++$run;
            $post = new Model_Post;
            $post->name = "newbie post $i";
            $post->topic = $topic;
            $user->posts->append($post);
        }
        
        $user->save();
        $posts = DB::select()->from('t_posts')->exec('jork_test');
        $this->assertEquals(10, count($posts));
        $users = DB::select()->from('t_users')->exec('jork_test'); 
        $this->assertEquals(5, count($users));
        $topics = DB::select()->from('t_topics')->exec('jork_test');
        $this->assertEquals(5, count($topics)); return;
    }

}