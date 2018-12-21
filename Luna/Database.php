<?php

namespace Luna;

class Database extends \PDO{

    public function __construct(string $dns, string $username, string $password, array $options = []){
        
        try{
            parent::__construct($dns, $username, $password, $options);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            throw new DbException($e->getMessage());
        }
    }
    
    public function query(string $sql, array $params = []){
        try{
            $stm = $this->prepare($sql);
            $stm->execute($params);
            return $stm;
        }catch (\PDOException $e){
            throw new QueryException($e->getMessage());
        }
    }
    
    public function insert(string $tableName, array $data){
        
        $sql = 'INSERT INTO ' . $tableName . ' (' . join(',', array_keys($data)) . ') VALUES (';
        
        foreach($data as $column => $value){
            if($value instanceof DbFunction){
                $sql .= $value->execute() . ',';
                unset($data[$column]);
            }else{
                $sql .= ':' . $column. ',';
            }
        }
        
        $sql = trim($sql, ',') . ')';
        
        return $this->query($sql, $data)->rowCount();
    }
    
    public function update(string $tableName, array $data, array $conditions = []){
        
        $sql = 'UPDATE ' . $tableName . ' SET ';
        
        foreach($data as $column => $value){
            if($value instanceof DbFunction){
                $sql .= $value->execute() . ',';
                unset($data[$column]);
            }else{
                $sql .= $column . '=:' . $column. ',';
            }
        }

        $sql = trim($sql, ',');
        
        $hasWhere = false;

        foreach($conditions as $column => $value){
            if($hasWhere == false){
                $sql .= ' WHERE ';
            }
            $sql .= $column . '=:c_' . $column .' AND ';
            $data['c_' . $column] = $value;
            $hasWhere = true;
        }
        
        $sql = trim($sql, ' AND ');
        return $this->query($sql, $data)->rowCount();
    }
}
