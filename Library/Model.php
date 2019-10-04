<?php

namespace Library;

use App\Exceptions\NoFillablesDefined;

class Model{

    private static $wheres = [];
    private static $withDeleted = false;

    protected $fillable = [];
    protected $softDeletes = false;

    public function toJson(){
        $properties = get_object_vars($this);
        $data = [];
        foreach($properties as $key => $value){
            $data[$key] = $value;
        }
        return json_encode($data);
    }

    public static function find($id){
        $calledClass = get_called_class();
        $table = get_object_vars(new $calledClass)['table'];
        $databaseHelper = new DatabaseHelper();
        $deleted = (self::$withDeleted === true)? " AND deleted_at IS NOT NULL" : "";
        $databaseHelper->setSql("SELECT * FROM ".$table." WHERE id = ?".$deleted);
        $result = $databaseHelper->getRow([$id]);
        self::$withDeleted = false;
        if($result){
            $model = new $calledClass($result);
            return $model;
        }
        return null;
    }

    public static function where($statement,$variables = null){
        self::$wheres[] = ['type' => 'AND', 'statement' => $statement,'variables' => $variables];;
        return get_called_class();
    }

    public static function orWhere($statement,$variables = null){
        self::$wheres[] = ['type' => 'OR', 'statement' => $statement,'variables' => $variables];;
        return get_called_class();
    }

    public static function get(){
        $wheres = self::$wheres;
        self::$wheres = [];
        $wherestring = "";
        $variables = null;
        for($i=0;$i<count($wheres);$i++){
            if($i == 0){
                $wherestring .= $wheres[$i]['statement'].' ';
            }else{
                $wherestring .= ' '.$wheres[$i]['type']." ".$wheres[$i]['statement'];
            }
            if($wheres[$i]['variables']){
                $variables[$i] = $wheres[$i]['variables'];
            }

        }

        $calledClass = get_called_class();
        $table = get_object_vars(new $calledClass)['table'];

        $databaseHelper = new DatabaseHelper();
        $deleted = (self::$withDeleted === true)? " AND deleted_at IS NOT NULL" : "";
        $databaseHelper->setSql("SELECT * FROM " . $table . " WHERE " . $wherestring.$deleted);
        $results = $databaseHelper->getRows($variables);

        $instances = [];
        for($i=0;$i<count($results);$i++){
            $instances[] = new $calledClass($results[$i]);
        }
        self::$withDeleted = false;
        return $instances;
    }

    public static function create($attr = []){
        $dbHelper = new DatabaseHelper();
        $calledClass = get_called_class();
        $classOptions = get_object_vars(new $calledClass);
        $table = $classOptions['table'];

        $fillable = $classOptions['fillable'];

        if(empty($fillable)){
            throw new NoFillablesDefined();
        }

        $fields = "";
        $values = "";
        $realValues = [];
        foreach($attr as $key => $value){
            if(in_array($key,$fillable)){
                $fields .= $key.",";
                $values .= "?,";
                $realValues[] = $value;
            }
        }
        $fields = trim($fields,",");
        $values = trim($values,",");
        $dbHelper->setSql("INSERT INTO ".$table." (".$fields.") VALUES (".$values.")");
        $id = $dbHelper->insertRecord($realValues);

        $dbHelper->setSql("SELECT * FROM ".$table." WHERE id = ?");
        $result = $dbHelper->getRow([$id]);
        if($result){
            return new $calledClass($result);
        }
        return null;
    }

    public static function withDeleted(){
        self::$withDeleted = true;

        return get_called_class();
    }

    public static function delete(){
        $dbHelper = new DatabaseHelper();
        $calledClass = get_called_class();
        $classOptions = get_object_vars(new $calledClass);
        $table = $classOptions['table'];
        if(array_key_exists("softdeletes",$classOptions) && $classOptions['softdeletes'] === true){
            $dbHelper->setSql("UPDATE ".$table." SET deleted = 1 WHERE id = ?");
            $dbHelper->updateRecord([$classOptions['id']]);
            return true;
        }
        $dbHelper->setSql("DELETE FROM ".$table." WHERE id = ?");
        $dbHelper->updateRecord([$classOptions['id']]);
        return true;
    }

    /**
     * @param $instance
     * @param $id
     * @param string $foreignKey
     * @param string $specific
     * @return object|null
     * @throws \Exception
     */
    protected function hasOne($instance,$id,$foreignKey="id",$specific="*"){
        $_db = new DatabaseHelper();
        $table = $instance::$table;
        $_db->setSql("SELECT $specific FROM $table WHERE $foreignKey = ?");
        $result = $_db->getRow([$id]);
        if($result){
            $model = new $instance($result);
            return $model;
        }
        return null;
    }

    /**
     * @param $model
     * @param string $foreignKey
     * @param integer $id
     * @param null $sortBy
     * @return array
     * @throws \Exception
     */
    protected function hasMany($model,$foreignKey,$id,$sortBy=null){
        $_db = new DatabaseHelper();
        $collection = [];
        $sortByItem = "";
        if(isset($sortBy)) {
            $sortByItem .= "ORDER BY " . $sortBy;
        }
        $table = $model::$table;
        $_db->setSql("SELECT * FROM $table WHERE $foreignKey = ? $sortByItem");
        $result = $_db->getRows([$id]);
        if($result){
            for($i=0;$i<count($result);$i++){
                $object = new $model($result[$i]);
                $collection[] = $object;
            }
        }
        return $collection;
    }

    public static function all(){
        $calledClass = get_called_class();
        $table = get_object_vars(new $calledClass)['table'];
        $databaseHelper = new DatabaseHelper();
        $deleted = (self::$withDeleted === true)? "  deleted_at IS NOT NULL" : " deleted_at IS NULL";
        $databaseHelper->setSql("SELECT * FROM ".$table." WHERE ".$deleted);
        $results = $databaseHelper->getRows();
        self::$withDeleted = false;
        $collection = [];
        if($results){
            foreach($results as $item){
                $collection[] = new $calledClass($item);
            }
        }
        return $collection;
    }
}
