<?php

class SimpleDB_Query_Result_ExecTest extends SimpleDB_Postgres_DbTest {

    public function testExecSelect() {
        $arr = DB::select()->from('users')->exec('postgres')->as_array();
        $this->assertEquals(array(
            array('id' => 1, 'name' => 'user1'),
            array('id' => 2, 'name' => 'user2')
        ), $arr);

        $result = DB::select()->from('users')->exec('postgres')->index_by('id');
        $exp_result = array(
            1 => array('id' => 1, 'name' => 'user1'),
            2 => array('id' => 2, 'name' => 'user2')
        ); 
        $cnt = 1;
        foreach ($result as $id => $row) {
            $this->assertEquals($cnt, $id);
            $this->assertEquals($exp_result[$cnt], $row);
            ++$cnt;
        }
        // we iterate again to check if rewind() works properly
        $cnt = 1;
        foreach ($result as $id => $row) {
            $this->assertEquals($cnt, $id);
            $this->assertEquals($exp_result[$cnt], $row);
            ++$cnt;
        }
    }

    public function testExecInsert() {
        $id = DB::insert('users')->values(array('name' => 'user3'))->exec('postgres');
        //$count = count(DB::select()->from('users')->exec('postgres')->as_array());
        //$this->assertEquals(3, $count);
        $this->assertEquals(3, $id);

        $id = DB::insert('serusers')->values(array('name' => 'user1'))->exec('postgres');
        $this->assertEquals(3, $id);

        $id = DB::insert('users')->values(array('name' => 'user1'))->exec('postgres', FALSE);
        $this->assertNull($id);
    }

    public function testExecDelete() {
        $affected = DB::delete('users')->where('id', '=', DB::esc(1))->exec('postgres');
        $this->assertEquals(1, $affected);
        
        $result = pg_query('select count(1) cnt from users');
        $row = pg_fetch_assoc($result);
        $this->assertEquals(1, $row['cnt']);
    }

    public function testExecUpdate() {
        $affected = DB::update('users')->values(array('name' => 'user2_mod'))
                ->where('id', '=', DB::esc(2))->exec('postgres');

        $this->assertEquals(1, $affected);

        $result = pg_query('select name from users where id = 2');
        $row = pg_fetch_assoc($result);
        $this->assertEquals('user2_mod', $row['name']);
    }

}
