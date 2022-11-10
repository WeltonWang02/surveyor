<?php

// DB Abstraction layer
Class DB {

    // Protected vars
    protected $db_host, $db_name, $db_username, $db_password;
    protected $instance;
    
    /**
     * Construct class
     *
     * @param  String $db_host
     * @param  String $db_name
     * @param  String $db_username
     * @param  String $db_password
     * @return void
     */
    public function __construct(String $db_host, String $db_name, String $db_username, String $db_password) {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        try {
            $this->instance = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_username, $this->db_password);
            $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    } 
    
    /// TODO: Error handling

    /**
     * query DB for content
     *
     * @param  String $query   Raw query with ? for substitutions
     * @param  Array $params    Array of data to substitue
     * @return void
     */
    public function query(String $query, Array $params = []){
        $stmt = $this->instance->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * insert data into DB 
     *
     * @param  String $table   Table to insert into
     * @param  Array $data    Array of data to insert
     * @return void
     */
    public function insert(String $table, Array $data){
        $keys = array_keys($data);
        $values = array_values($data);
        $query = "INSERT INTO $table (`".implode("`, `", $keys)."`) VALUES (".implode(",", array_fill(0, count($values), "?")).")";     
        $stmt = $this->instance->prepare($query);
        $stmt->execute($values);
        return $stmt->rowCount();
    }
    
    /**
     * Check if MySQL table exists
     *
     * @param  String $table
     * @return void
     */
    public function check_table(String $table){
        $stmt = $this->instance->prepare("SHOW TABLES LIKE '$table'");
        $stmt->execute();
        return $stmt->rowCount();
    }

}  