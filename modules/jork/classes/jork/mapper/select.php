<?php

/**
 * Maps a jork select to a db select.
 */
class JORK_Mapper_Select {

    protected $_jork_query;

    protected $_db_query;

    protected $_mappers;

    protected $_naming_service;

    protected $_has_implicit_root;

    public function  __construct(JORK_Query_Select $jork_query) {
        $this->_jork_query = $jork_query;
        $this->_db_query = new DB_Query_Select;
        $this->_naming_service = new JORK_Naming_Service;
    }

    public function map() {

        $this->_has_implicit_root = count($this->_jork_query->from_list) == 1
                &&  ! array_key_exists('alias', $this->_jork_query->from_list[0]);

        $this->map_from();

        $this->map_join();

        $this->map_with();

        $this->map_select();

        return array($this->_db_query, $this->_mappers);
    }

    protected function map_from() {
        if ($this->_has_implicit_root) {
            $from_item = $this->_jork_query->from_list[0];
            $this->_naming_service->set_implicit_root($this->_jork_query->from_list[0]['class']);
            $schema = $this->_naming_service->get_schema($from_item['class']);
            $this->_db_query->tables []= array($schema->table
                    , $this->_naming_service->table_alias($from_item['class'], $schema->table));
        } else {
            foreach ($this->_jork_query->from_list as $from_item) {
                $this->_naming_service->set_alias($from_item['class'], $from_item['alias']);
                $schema = $this->_naming_service->get_schema($from_item['alias']);
                $this->_db_query->tables []= array($schema->table
                    , $this->_naming_service->table_alias($from_item['alias'], $schema->table));
            }
        }
    }

    protected function map_join() {
        
    }

    protected function map_with() {
        
    }

    protected function map_select() {
        if ($this->_has_implicit_root) {
            //empty select list with implicit root entity
            $root_schema = JORK_Model_Abstract::schema_by_class($this->_jork_query->from_list[0]['class']);
            $tbl_alias = $this->_naming_service->table_alias($root_schema->class, $root_schema->table);
            if (empty($this->_jork_query->select_list)) {
                foreach ($root_schema->columns as $col_name => $col_def) {
                    $this->_db_query->columns []= $tbl_alias.'.'
                            .(array_key_exists('db_column', $col_def) ? $col_def['db_column'] : $col_name);
                }
            } else {
                $this->add_select_items();
            }
        }
    }

    protected function add_select_items() {
        foreach ($this->_jork_query->select_list as $select_item) {
            $prop_chain = $select_item['prop_chain']->as_array();
            
        }
    }

}