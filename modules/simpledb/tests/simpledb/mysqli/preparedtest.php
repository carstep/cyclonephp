<?php


class SimpleDB_Mysqli_PreparedTest extends SimpleDB_Mysqli_DbTest {

    public function testPrepareSelect() {
        $query = DB::select()->from('user')->prepare();
        $this->assertInstanceOf('DB_Query_Prepared_Select', $query);
    }

    public function testPrepareInsert() {
        $query = DB::insert('user')->values(array('id' => 1, 'name' => 'u'))->prepare();
        $this->assertInstanceOf('DB_Query_Prepared_Insert', $query);
    }

    public function testPrepareUpdate() {
        $query = DB::update('user')->values(array('id' => 1))->prepare();
        $this->assertInstanceOf('DB_Query_Prepared_Update', $query);
    }

    public function testPrepareDelete() {
        $query = DB::delete('user')->prepare();
        $this->assertInstanceOf('DB_Query_Prepared_Delete', $query);
    }

    public function testPrepareCustom() {
        $query = DB::query('select id')->prepare();
        $this->assertInstanceOf('DB_Query_Prepared_Custom', $query);
    }
    
}