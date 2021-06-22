<?php

abstract class Model
{
    protected static $conn;
    protected $columns=[];
    protected $values=[];
    protected $table;
    protected  $q;
    protected  $fetch;

    protected const HOST = 'localhost';
    protected const USER = 'root';
    protected const PASS = '';
    protected const DB = 'MVC';

    public function __construct()
    {
        return self::$conn = new mysqli(self::HOST,self::USER,self::PASS,self::DB);

        
    }

    public function create($data)
    {
        foreach($data as $key => $para){
            if(array_key_first($data) == $key){
                $this->columns = $key;
                
                $this->values = "'".$para."'";
            }else{
                $this->columns .= ','.$key;
                $this->values .= ",'".$para."'";
            }
        }
        $this->q = "INSERT INTO ".$this->table." (".$this->columns.") VALUES ($this->values)";
        if(self::$conn->query($this->q)){
            echo "Success";
            $this->q = '';
        }else{
            echo "failed: Email is already taken";
        }
        
    }

    public function all(){
        $this->q = "SELECT * FROM ".$this->table."";
        
        $results = self::$conn->query($this->q);
        $i = 0;
        $obj = [];
        while ($row = $results->fetch_array()){
            
               foreach($this->columns as $key => $column){
                    if(array_key_first($this->columns) == $key){
                        if($i == 0){
                            $this->fetch = [$i => [$column => $row[$column]]];
                        }else{
                            $this->fetch = array_merge($this->fetch, [$column => $row[$column]]);
                        }
                    }else{
                        $this->fetch = array_merge($this->fetch[$i], [$column => $row[$column]]);
                        
                    }
                    if(array_key_last($this->columns) == $key){
                        $i =0;
                    }
                    
                } 
                if($i < 1){
                    $obj = $this->fetch;
                }else{
                    array_merge($obj,$this->fetch);
                }
                $i++;
        }
        return  $obj;
    }

    public function where($col,$opt = null,$val=null){
        if($opt!=null && $val==null){
            $val=$opt;
            $opt = '=';
        }
            if(!empty(self::$q)){
                self::$q .= " WHERE $col $opt $val";
            }else{
                self::all();
                self::$q .= " WHERE $col $opt $val";
            }
        
        echo self::$q;
    }
}

class Student extends Model
{
    protected $table = 'users';
    protected $columns = ['name', 'email'];
}


$stu = new Student;
// $data = [
//     'name' => 'Zack',
//     'email' => 'zack@gmail.com',
//     'password' => 'secret',
// ];
// $stu->create($data);

$users = $stu->all();

var_dump($users);
// $users = $user = $users;

// foreach($users as $user){
//      echo $users->name;
// }

// foreach($users as $user){
//     echo $user;
// }   
