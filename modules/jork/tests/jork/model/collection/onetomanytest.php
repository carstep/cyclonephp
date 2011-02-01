<?php


class JORK_Model_Collection_OneToManyTest extends Kohana_Unittest_TestCase {

    public function testAppend() {
        $user = new Model_User;
        $user->id = 15;
        $post = new Model_Post;
        $user->posts->append($post);
        $this->assertEquals(15, $post->user_fk);
        $this->assertEquals(1, count($user->posts));
        //$this->assertEquals($user, $post->author);
    }

    public function testDelete() {
        $user = new Model_User;
        $user->id = 15;
        $post = new Model_Post;
        $post->id = 12;
        $user->posts->append($post);

        unset($user->posts[12]);
        $this->assertEquals(0, count($user->posts));
    }
}