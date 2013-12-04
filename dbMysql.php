<?php
/*
 * Author Yusuf Hermanto
 */
require_once 'dbBase.php';

class dbMysql extends dbBase
{
    
    public function init()
    {
        $this->connect();
    }
    
    /*
     * Connect to Database
     */
    protected function connect()
    {
       
        
        $this->connection = mysql_connect(
                $this->config['host'],
                $this->config['username'],
                $this->config['password']
                );
        
        if($this->connection == false){
           throw new Exception(mysql_error());
        }
        
        mysql_query("SET NAMES 'utf8'", $this->connection);
        mysql_query("SET CHARACTER SET utf8", $this->connection);
        mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->connection);
        mysql_query("SET SQL_MODE = ''", $this->connection);

        if(@mysql_select_db($this->config['dbname'], $this->connection) == false) {
            
        }

        if (!empty($this->config['charset'])) {
            mysql_set_charset($this->config['charset'], $this->connection);
        }
       
    }
    
    public function query_array($query){
        $rs = mysql_query($query, $this->connection);
        
        return $rs;
    }
    
    public function query_object($query){
        $rs = mysql_query($query, $this->connection);
        return mysql_fetch_object($rs);
    }
    
    public function escape($value) {
        if ($this->connection) {
                return mysql_real_escape_string($value, $this->link);
        }
    }
	
        
    public function countAffected() {
        if ($this->connection) {
            return mysql_affected_rows($this->connection);
        }
    }

    public function lastInsertId()
    {
        return (string)mysql_insert_id($this->connection);
    }
    
    
    /**
    * Begin a transaction.
    */
    protected function beginTransaction()
    {
        $this->connect();
        mysql_query("START TRANSACTION");
    }

    /**
    * Commit a transaction.
    */
    protected function commit()
    {
        mysql_query("COMMIT");
    }

    /**
    * Roll-back a transaction.
    */
    protected function rollBack()
    {
        mysql_query("ROLLBACK");
    }
    
    /**
    * Check if a connection is active
    *
    * @return boolean
    */
    public function isConnected()
    {
        return(is_resource($this->connection));
    }

    /**
    * Force the connection to close.
    *
    * @return void
    */
    public function closeConnection()
    {
        if($this->isConnected()) {
            mysql_close($this->connection);
            $this->connection = null;
        }
    }
    
    public function version()
    {
       
    }
    
}

?>
