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
}
