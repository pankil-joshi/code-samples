<?php

/**
 * Database wrapper class.
 * 
 * @package Quill
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 */

namespace Quill;

use Quill\MysqlPdo as MysqlPdo;
use Quill\Exceptions\DatabaseException;

class Database extends MysqlPdo{
    
    /**
     * Table name of the databse to be set.
     * 
     * @var string 
     */
    
    protected $tableName = '';
    
    /**
     *
     * @var string 
     */
    
    protected $primarykey = 'id';
    
    /**
     *
     * @var array 
     */
    
    protected $acceptable = array();
    
    /**
     *
     * @var array 
     */
    
    protected $hidden = array();

    /**
     *
     * @var string 
     */
    
    private $columns = '';
    
    /**
     *
     * @var string 
     */
    
    private $where = '';
    
    /**
     *
     * @var string 
     */
    
    private $join = '';
    
    /**
     *
     * @var array 
     */
    
    private $values = array();
    
    /**
     *
     * @var mixed 
     */
    
    private $limit;
    
    /**
     * 
     * @return \Quill\Database
     */
    
    private $cache;
    
    /**
     *
     * @var string 
     */
    
    private $lock;
    
    /**
     *
     * @var string 
     */
    
    private $groupBy;

    /**
     *
     * @var string 
     */
    
    private $orderBy;
    
    /**
     *
     * @var string 
     */
    private $distinct;
    
    

    /**
     * This method doesn't do anything fancy otherthan returning the database object.
     * 
     * @return \Quill\Database
     */
    protected function table() {
        
        return $this;
        
    }
    
    /**
     * 
     * @param type $tableName
     * @return type
     */
    public function setTableName($tableName) {
        
        return $this->tableName = $tableName;
        
    }    

    /**
     * Insert single row of data into the database table.
     * 
     * @param array $data Associative array of data to be inserted into the table.
     * @return int Returns last insert id.
     * @uses array_keys
     * @uses count()
     * @uses implode()
     * @uses array_values()
     * @throws Exception 
     */
    
    public function insert(array $data, $response = false) {

        $columns = '';
        $placeholders = '';
        $last_iteration = false;

        $countArray = count($data);  
        
        foreach ($data as $key => $value) {
               
            $last_iteration = !(--$countArray);                
            
            $columns .= $key;
            $columns .= (!$last_iteration)? ', ' : '';

            $placeholders .= '?';
            $placeholders .= (!$last_iteration)? ', ' : '';
            
            $values[] = $value;

        }
        
        try {
            
            $stmt = $this->getConnection()->prepare("INSERT INTO {$this->tableName}({$columns}) VALUES({$placeholders}) "); 
//                                    var_dump($stmt, $values);
//                                    exit();
            $stmt->execute($values);

            if($response === true) {
                         
                return $this->select()->where(array($this->primarykey => $this->getConnection()->lastInsertId()))->one();
                
            } else {
              
                return $this->getConnection()->lastInsertId();
                
            }             
            
        } catch (\Exception $exc) {

            throw new DatabaseException('Database error: '. $exc->getMessage());
            
        }
        
    }
    
    /**
     * Update single row of data in the database table.
     * 
     * @param array $data Associative array of data to be updated.
     * @param array $where Associative array of whrere conditions.
     * @param boolean $custom Set true if you want to set where oparator other than '='.
     * @return mixed Number of rows updated or false.
     * @uses array_keys()
     * @uses array_values()
     * @uses count()
     * 
     */
    
    public function update(array $data, $returnData = false, $debug = false ) {
        
        $uniqueKey = (!empty($uniqueKey))? $uniqueKey : $this->primarykey;
        
        $setQuery = '';
        
        $last_iteration = false;

        $countArray = count($data);        
        
        foreach ($data as $key => $value) {
               
            $last_iteration = !(--$countArray);
                
            $setValues[] = $value;

            $setQuery .= $key.' = ? ';
                
            $setQuery .= (!$last_iteration)? ', ' : '';

        }
        
        try {
            
            $stmt = $this->getConnection()->prepare("UPDATE {$this->tableName} SET {$setQuery} {$this->getQuery()} ");
            if($debug === true) var_dump($stmt, array_merge($setValues, $this->values));
            $stmt->execute(array_merge($setValues, $this->values));              
            
            if($returnData === true) {

                $data = $this->select()->one();
                
            } else {
                
                $data = $stmt->rowCount();
                
            }
            
            $this->_clearVars();
            
            return $data;
            
        } catch (\Exception $exc) {
            
            throw new DatabaseException('Database error: '. $exc->getMessage());
            
        }
        
    }
    
    public function incrementField($field, $value = 1) {        
        
        try {
            
            $stmt = $this->getConnection()->prepare("UPDATE {$this->tableName} SET {$field} = $field + $value {$this->getQuery()} ");
            $stmt->execute($this->values);            
            
            $data = $stmt->rowCount();
            
            $this->_clearVars();
            
            return $data;
            
        } catch (\Exception $exc) {
            
            throw new DatabaseException('Database error: '. $exc->getMessage());
            
        }
        
    }    
    
    /**
     * 
     * @param array $where
     * @param type $custom
     * @return type
     */
    
    public function one($filter = true, $debug = false) {

        try {
            
            $this->limit(1);
            
            $stmt = $this->getConnection()->prepare("SELECT {$this->_getColumns()} from {$this->tableName} {$this->getQuery()} ");
            if($debug === true) var_dump($stmt, $this->values);
            $stmt->execute($this->values);
            
            $response = ($filter)? $this->_filterResponse($stmt->fetch(\PDO::FETCH_ASSOC)) : $stmt->fetch(\PDO::FETCH_BOTH);
            
            $this->_clearVars();

            return $response;
            
        } catch (\Exception $exc) {
            $debug = ($debug === true)? 'query: ' . $stmt->queryString . '; params: ' . implode(',', $this->values) . ';': '';
            throw new DatabaseException('Database error: '. $exc->getMessage(). ' ' . $debug);
            
        }
        
    }
    
    public function all($filter = true, $debug = false) {

        try {
            
            $stmt = $this->getConnection()->prepare("SELECT {$this->distinct} {$this->_getColumns()} from {$this->tableName} {$this->getQuery()} ");
            if($debug === true) var_dump($stmt, $this->values);
            $stmt->execute($this->values);
            
            $response = ($filter)? $this->_filterResponse($stmt->fetchAll(\PDO::FETCH_ASSOC)) : $stmt->fetchAll(\PDO::FETCH_BOTH);
            
            $this->_clearVars();
            
            return $response;
            
        } catch (\Exception $exc) {
            $debug = ($debug === true)? 'query: ' . $stmt->queryString . '; params: ' . implode(',', $this->values) . ';': '';
            throw new DatabaseException('Database error: '. $exc->getMessage(). ' ' . $debug);
            
        }
        
    }  
    
    public function _filterResponse($data) {

        if($this->_getColumns() === '*') {
            
            if(isset($data[0])){
                
                foreach ($data as $row) {

                    $filtered[] =  array_diff_key($row, array_flip($this->hidden));

                }

                return $filtered;

            } elseif(!empty($data)) {

                return array_diff_key($data, array_flip($this->hidden));

            } else {
                
                return $data;
                
            }
            
        } else {
            
            return $data;
            
        }
        
    }

    public function field($columnIndex = 0, $debug = false) {

        try {
            
            $stmt = $this->getConnection()->prepare("SELECT {$this->_getColumns()} from {$this->tableName} {$this->getQuery()} ");
            if($debug === true) var_dump($stmt, $this->values);
            $stmt->execute($this->values);
            
            $this->_clearVars();
            
            return $stmt->fetchColumn($columnIndex);
            
        } catch (\Exception $exc) {
            $debug = ($debug === true)? 'query: ' . $stmt->queryString . '; params: ' . implode(',', $this->values) . ';': '';
            throw new DatabaseException('Database error: '. $exc->getMessage(). ' ' . $debug);
            
        }
        
    }
    
    public function delete() {
        
        try {
            
            $stmt = $this->getConnection()->prepare("DELETE from {$this->tableName} {$this->getQuery()}");
            $stmt->execute($this->values);

            $this->_clearVars();
            
            return $stmt->rowCount();
            
        } catch (\Exception $exc) {
            
            throw new DatabaseException('Database error: '. $exc->getMessage());
            
        }        
        
    }    
    
    public function rawQuery($query) {
        
        try {
            
            $stmt = $this->getConnection()->query($query, \PDO::FETCH_ASSOC);

            return $stmt;
            
        } catch (\Exception $exc) {
            
            throw new DatabaseException('Database error: '. $exc->getMessage());
            
        }        
        
    }    
    
    public function upsert(Array $data, $debug = false) {
        
        $columns = '';
        $update = '';
        $placeholders = '';
        $insertValues = array();
        $updateValues = array();
        $values = array();
        $last_iteration = false;

        $countArray = count($data);
        
        foreach ($data as $key => $value) {
            
            $last_iteration = !(--$countArray);
            
            $insertValues[] = $value;
            $updateValues[] = $value;
            
            $columns .= $key;
            $columns .= (!$last_iteration)? ', ': '';
            
            $update .= $key . ' = ?';
            $update .= (!$last_iteration)? ', ': '';
            
            $placeholders .= '?';
            $placeholders .= (!$last_iteration)? ', ' : '';              
            
        }
        
        try {
            
            $stmt = $this->getConnection()->prepare("INSERT INTO {$this->tableName}({$columns}) VALUES({$placeholders}) ON DUPLICATE KEY UPDATE {$update} ");
            
            $values = array_merge($insertValues, $updateValues);
            if($debug === true) var_dump($stmt, $values);
            return $stmt->execute($values); 
            
        } catch (\Exception $exc) {
            $debug = ($debug === true)? 'query: ' . $stmt->queryString . '; params: ' . implode(',', $values) . ';': '';
            throw new DatabaseException('Database error: '. $exc->getMessage(). ' ' . $debug);
            
        }       
        
    }
    
    public function beginTransaction() {
        
        return $this->getConnection()->beginTransaction();
        
    }
    
    public function commit() {
        
        return $this->getConnection()->commit();
        
    }
       
     public function rollback() {
        
        return $this->getConnection()->rollback();
        
    }    

    private function _buildWhere($outerOperator, $innerOperator, array $where = array(), $custom = false, $between = false, $in = false) {
        
        $countWhere = count($where);        
        
        if($countWhere !== 0) {

            if(empty($this->where)) {

                $this->where = ' WHERE ';

            } else {

                $this->where .= ' '.$outerOperator.' ';

            }
            
            $this->where .= ' ( ';
            $last_iteration = false;

            foreach ($where as $key => $value) {

                $last_iteration = !(--$countWhere);
                
                if(is_array($value)) {
                   
                    foreach ($value as $row){
                        
                        $this->values[] = $row;
                    }
                    
                } else {
                    
                    $this->values[] = $value;
                }

                if(true === $custom) {

                    $this->where .= $key . ' ? ';

                } else {
                    
                    if(true === $between) {
                        
                        $this->where .= $key . ' BETWEEN ? AND ?';
                    } elseif(true === $in) {
                        
                        $valuesCount = count($value);
                        $qMarks = str_repeat('?,', $valuesCount - 1) . '?';
  
                        $this->where .= $key . ' IN (' . $qMarks . ')';
                    } else {
                        
                        $this->where .= $key . ' = ? ';
                    }

                }

                $this->where .= (!$last_iteration)? ' '.$innerOperator.' ' : '';

            }
            
            $this->where .= ' ) ';
        
        }       
        
    }
    
    public function where(array $where = array(), $custom = false) {
        
        $this->_buildWhere('AND', 'AND', $where, $custom);
        
        return $this;
        
    }
    
    public function orWhere(array $where = array(), $custom = false) {
        
        $this->_buildWhere('OR', 'OR', $where, $custom);
        
        return $this;
    } 
    
    public function andOrWhere(array $where = array(), $custom = false) {
        
        $this->_buildWhere('AND', 'OR', $where, $custom);
        
        return $this;
    }
    
    public function whereBetween(array $where = array(), $custom = false) {
        
        $this->_buildWhere('AND', 'AND', $where, false, true);
        
        return $this;
    }
    
    public function whereIn(array $where = array(), $custom = false) {
        
        $this->_buildWhere('AND', 'AND', $where, false, false, true);
        
        return $this;
    }    
    
    protected function getQuery() {
        
        
        $limit = null;

        if(!empty($this->limit) && is_array($this->limit)) {
            
            $limit .= 'LIMIT ' . $this->limit[0] . ',' . $this->limit[1];
            
        } elseif(!empty($this->limit) && is_int($this->limit)) {
            
             $limit .= 'LIMIT ' . $this->limit;
            
        }

        return $this->join . $this->where . ' ' . $this->groupBy . ' '. $this->orderBy .' '. $limit . ' ' . $this->lock;
        
    }
    
    public function select($columns = '*', $as = '') {

        $this->columns .= (!empty($this->columns))? ', ' : '';
        $this->columns .= $columns;       
        $this->columns .= (!empty($as))?  ' as ' . $as : '';
        
        return $this;
        
    }
    
    public function count($columns = '*', $as = '') {

        $this->columns .= (!empty($this->columns))? ', ' : '';
        $this->columns .= "count({$columns})";       
        $this->columns .= (!empty($as))?  ' as ' . $as : '';
        
        return $this;
        
    }    
    
    public function selectGroup(array $columns, $tablename, $aliasPrefix) {
        
        $this->columns .= (!empty($this->columns))? ', ' : '';
        
        $columnCount = count($columns); 
        $last_iteration = false;
        
        foreach ($columns as $column) {
            
            $last_iteration = !(--$columnCount);
            
            $this->columns .= $tablename.'.'.$column;       
            $this->columns .= ' as ' . $aliasPrefix . '_' . $column;                
            $this->columns .= (!$last_iteration)? ', ' : '';
            
        }
        
        return $this;
        
    }
    
    private function _getColumns() {

        return $this->columns;
        
    }
    
    protected function limit($limit) {
        
        $this->limit = $limit;
        
        return $this;
        
    }
    
    protected function distinct() {
        
        $this->distinct = 'distinct';
        
        return $this;
        
    }


    protected function join($table, $primary, $foriegn) {
        
//        $this->tableName = $this->tableName . ' , ' . $table;
        
        $this->join .= ' JOIN ' . $table . ' ON ' . $primary . ' = ' . $foriegn;
        
        return $this;
        
    }
    
    protected function leftJoin($table, $primary, $foriegn = '') {
        
//        $this->tableName = $this->tableName . ' , ' . $table;
        if(is_array($primary)) {
            
            $columnCount = count($primary); 
            $last_iteration = false;
            
            $this->join .= ' LEFT JOIN ' . $table . ' ON ';
            
            foreach ($primary as $key => $value) {
                
                $last_iteration = !(--$columnCount);
                
                $this->join .= $key . ' = ' . $value;
                
                $this->join .= (!$last_iteration)? ' AND ' : '';
            }
            
        } else {
            
            if(empty($foriegn)) {
                
                throw new DatabaseException('Foriegn key not set!');
                
            }
            
            $this->join .= ' LEFT JOIN ' . $table . ' ON ' . $primary . ' = ' . $foriegn;
            
        }
        
        return $this;
        
    }
    
    protected function rightJoin($table, $primary, $foriegn) {
        
//        $this->tableName = $this->tableName . ' , ' . $table;
        
        $this->join .= ' RIGHT JOIN ' . $table . ' ON ' . $primary . ' = ' . $foriegn;
        
        return $this;
        
    }
    
    protected function groupBy($column) {
        
        $this->groupBy .= (!empty($this->groupBy))? ', ' : '';
        $this->groupBy .= ' GROUP BY ' . $column;
        
        return $this;
        
    }
    
    protected function orderBy($column, $order = 'ASC') {
        
        $this->orderBy .= (!empty($this->orderBy))? ', ' : '';
        $this->orderBy .= 'ORDER BY ' . $column;
        $this->orderBy .= ' ' . $order;
        
        return $this;
        
    }    


    protected function lock($lock) {
        
        $this->lock = $lock;
        
        return $this;
        
    }


    private function _clearVars() {
        
        $this->columns = '';

        $this->where = '';
    
        $this->join = '';
    
        $this->values = array();
    
        $this->limit = null;
        
        $this->cache = array();
        
        $this->lock = '';
        
        $this->groupBy = '';
        
        $this->orderBy = '';
        
        $this->distinct = '';
        
    }
    
    public function getCache($id) {
       
       if(empty($this->cache[$id])) { 
           
           $this->cache[$id] = $this->getById($id);
           
       }

        return $this->cache[$id];
        
    }
    
    
}