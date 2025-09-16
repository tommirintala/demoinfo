<?php
/*
  Copyright 2025 Tommi Rintala <tommi.rintala@vamk.fi>
 
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  
     http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

$pages = array(
    0 => [
        'id' => '0198d23b-e09d-7188-b27e-6812e26cff8b',
        'title' => 'Demo page 1',
        'content' => 'This is demo content',
        'app' => 'apps/demo1.php',
    ],
    1 => [
        'id' => '0198d256-b969-7072-884b-42197c3f75ef',    
        'title' => 'Page 2',
        'content' => 'This is demo content too',
        'app' => 'apps/demo2.php',
    ],
    2 => [
        'id' => '0198d29b-161c-7099-94b8-6d9f05ad75f5',
        'title' => 'Page 3',
        'content' => 'Page 3 content',
        'app' => 'apps/demo3.php',
    ],
);

define('TYPE_STRING', "TEXT");
define('TYPE_INT', "INT");
define('TYPE_KEY', "PRIMARY KEY");
define('TYPE_BOOL', "BOOL");


class ORM {

    // array<string, bool>
    private $_dirty;
    // array<string, array>
    private $_fields;
    
    private ?string $_obj;
    
    public function __construct(string $obj, array $init = []) {
        $this->_obj = $obj;
        $this->_dirty = [];
        $this->_fields = [];
        foreach ($init as $key => $val) {
            $this->addField( $val['name'],
                             isset($val['type']) ? $val['type'] : TYPE_STRING,
                             isset($val['default']) ? $val['default'] : '',
                             isset($val['value']) ? $val['value'] : ''
            );
        }
    }
    
    public function addField(string $name, string $type, ?string $defaultvalue, ?string $value = NULL): void {
        $this->_fields[$name] = [ 'type' => $type, 'default' => $defaultvalue, 'value' => $value ];
        $this->_dirty[$name] = false;
    }
    
    public function get(string $name, string $default = "") {
        return isset($this->_content[$name]) ? $this->_content[$name] : $default;
    }
    
    public function set(string $name, string $value): void {
        if (isset($this->_content[$name]) || $this->_content[$name] != $value) {
            $this->_dirty[$name] = true;
        }
        $this->_content[$name] = $value;        
    }
    
    public function insert(): void {
    }
    
    public function find(string $id): void {
    }
    
    public function store(string $key): void {
        
    }

    public function create_sql(int $rev = 0): string {
        $sql = "CREATE TABLE " . $this->table_name() . "( ";
        $fd = array();
        if (!in_array($this->_fields, 'id')) {
            $fd[] = "id " . TYPE_KEY;
        }
        foreach ($this->_fields as $f => $t) {
            $fd[] = "$f " . $t['type'];
        }
        $sql .= join(", ", $fd);
        $sql .= ");";
        return $sql;
    }
    
    public function drop_sql(): string {
        return join(' ', array("DROP TABLE", $this->table_name(), "IF EXISTS;"));
    }

public function table_name(): string {
return $this->_obj;
}
}

class ContentModule {

    private $_content = NULL;
    public function __construct() {
        $this->_content = new ORM('content', [
            [ 'name' => 'id', 'type' => TYPE_STRING ],
            [ 'name' => 'cols', 'type' => TYPE_STRING ],
            [ 'name' => 'content', 'type' => TYPE_STRING ],
            [ 'name' => 'enabled', 'type' => TYPE_BOOL ],
        ]);
    }

    /**
     * Update content to database
     */
    public function store():void {
    }
    /**
     * Load content from database
     */
    public function load():void {
    }
}
