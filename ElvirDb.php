<?php

  class ElvirDb extends PDO
  {
    private $table,$join = false,$where = false,$set = false,$executeArray = [],$sql,$columns;

    public function __construct($HOST,$DBNAME,$USER,$PASSWORD) {

      try {

        parent::__construct("mysql:hostname=".$HOST.";dbname=".$DBNAME.";charset=utf8",$USER,$PASSWORD);

      }catch(PDOException $e) {

        echo $e->getMessage();

      }

    }

    public function select(Array $data = ['*']) {

      $this->columns = $data;
      $this->sql = "SELECT ";

      foreach($this->columns as $column):
        $this->sql .= "$column,";
        
      endforeach;

      echo rtrim($this->sql,',');
    }

    public function from(String $table) {
      $this->table = $table;
      $this->sql .= "FROM $this->table ";
      
    }

    public function insert(String $table) {

      $this->table = $table;
      $this->sql = "INSERT INTO $this->table ";

      return $this;

    }

    public function delete(String $table) {
      $this->table = $table;
      $this->sql = "DELETE FROM $this->table ";

      return $this;
    }

    public function where($column,$value,$deliminater = "&&") {
      
      if(!$this->where) {
        $this->where = "WHERE $column=:$column";
        $this->executeArray = [$column=>$value];
      } else {
        $this->where .= " $deliminater $column=:$column";
      }
      
      return $this;
    }

    public function set($data) {
      $this->set = "SET";

      foreach($data as $key => $value):
        $this->set .= " $key=:$key,";
      endforeach;

      $this->set = rtrim($this->set,',');
      $this->executeArray = $data;

      return $this;

    }

    public function allOrOne(String $type) {
      
    }

    public function run() {

      $this->sql .= $this->join.$this->set.$this->where;

      $this->sql = rtrim($this->sql);
      
      $sth = $this->prepare($this->sql);
      $sth->execute($this->executeArray);

      if($sth->rowCount()) {
        return true;
      }
      return false;
    }

  }
  

?>