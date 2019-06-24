<?php
Class DB{
    static public $conn;
    static public $result;
    static public $last_id;
    static public $posts;
    static public $tableName;
    static public $selectables = array();
    static public $table;
    static public $whereClause;
    static public $orderbyClause;
    static public $limit;
    static public $token;
    
    static public function connection(){
        include "config.php";
        static::$token = $db_token;
        $data = func_get_args();
        if(count($data) == 0){
            $dd = $db;
            static::$conn = mysqli_connect($dd[0],$dd[1],$dd[2],$dd[3]);
            if (static::$conn->connect_error) {echo "Connection Failed";}
            mysqli_set_charset (static::$conn, 'utf8');
        }
        else{
            $dd = $data;
            static::$conn = mysqli_connect($dd[0],$dd[1],$dd[2],$dd[3]);
            if (static::$conn->connect_error) {echo "Connection Failed";}
            mysqli_set_charset (static::$conn, 'utf8');
        }
        static::$tableName = static::class;
    }
    static public function table($a){
        static::$tableName = $a;
    }
    //insert into table
    static public function save(array $values){
        $tableName = static::$tableName;
        $columns = implode(", ",array_keys($values));
        foreach ($values as $idx=>$data) $insdata[$idx] = "'".$data."'";
        $insvalues = implode(", ",$insdata);
        $query = "INSERT into $tableName ($columns) VALUES ($insvalues)";
        if(static::$conn->query($query) == true){
            static::$last_id = static::$conn->insert_id;
            return static::$result = "success";
        }
        else{return static::$result = static::$conn->error;}
    }
    // select from table with id
    static public function find($id){
        $tableName = static::$tableName;
        $query = "SELECT * from $tableName where id = '$id'";
        $result = static::$conn->query($query);
        return static::$result = $result;
    }
    //select from tabel with other condition
    static public function findCondition($condition){
        $tableName = static::$tableName;
        $query = "SELECT * from $tableName $condition";
        $result = static::$conn->query($query);
        return static::$result = $result;
    }
    
    //select all rows from tabele
    static public function all(){
        $tableName = static::$tableName;
        $query = "SELECT * from $tableName order by id desc";
        $result = static::$conn->query($query);
        return static::$result = $result;
    }
    //delect with id
    static public function destroy($id){
        $tableName = static::$tableName;
        $query= "DELETE from $tableName where id= '$id'";
        if(static::$conn->query($query) == true){
            return static::$result = "success";
        }
        else{return static::$result = static::$conn->error;;}
    }
    //delect with condition
    static public function destroyWhere($condition){
        $tableName = static::$tableName;
        $query= "DELETE from $tableName $condition";
        if(static::$conn->query($query) == true){
            return static::$result = "success";
        }
        else{return static::$result = static::$conn->error;;}
    }
    //update with id 
    static public function update($id, array $data){
        $tableName = static::$tableName;
        foreach($data as $key => $value){$okv[$key] = $key. "='". $value."'";}
        $ok = implode(", ",$okv);
        $query = "UPDATE $tableName SET $ok where id = '$id'";
        if(static::$conn->query($query) == true){
            return static::$result = "success";
        }
        else{return static::$result = static::$conn->error;;}
    }
    static public function updateWhere($condition, array $data){
        $tableName = static::$tableName;
        foreach($data as $key => $value){$okv[$key] = $key. "='". $value."'";}
        $ok = implode(", ",$okv);
        $query = "UPDATE $tableName SET $ok $condition";
        if(static::$conn->query($query) == true){
            return static::$result = "success";
        }
        else{return static::$result = static::$conn->error;;}
    }
    static public function secure($data){
        $db =static::$conn;
        function change($haha,$db){
           return htmlspecialchars(mysqli_real_escape_string($db,trim($haha)));
        }
        return array_map(
            function($input) use ($db) { return change($input, $db); },$data);
    }
    static public function query($data){
        $query = $data;
        $result = static::$conn->query($query);
        return static::$result = $result;
    }
    
    
    static public function select() {
        static::$selectables = func_get_args();
        return new static();
    }
    static public function where($where) {
        static::$whereClause = $where;
        return new static();
    }
    static public function orderby($orderby){
        static::$orderbyClause = $orderby;
        return new static();
    }
    static public function limit($limit) {
        static::$limit = $limit;
        return new static();
    }
    static public function get() {
        $query[] = "SELECT";
        // if the selectables array is empty, select all
        if (empty(static::$selectables) || strtolower(static::$selectables[0])=='all') {
            $query[] = "*";  
        }
        // else select according to selectables
        else {
            $query[] = join(', ', static::$selectables);
        }
        $query[] = "FROM";
        $query[] = static::$tableName;
        if (!empty(static::$whereClause)) {
            $query[] = "WHERE";
            $query[] = static::$whereClause;
        }
        if(!empty(static::$orderbyClause)){
            $query[] = "ORDER BY";
            $query[] = static::$orderbyClause;
        }
        if (!empty(static::$limit)) {
            $query[] = "LIMIT";
            $query[] = static::$limit;
        }
        $query = join(' ', $query);
        $result = static::$conn->query($query);
        return static::$result = $result;
    }
    static public function closeConnection(){
        mysqli_close(static::$conn);
    }
}
?>
