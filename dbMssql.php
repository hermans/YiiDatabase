<?php
/*
 * Author Yusuf Hermanto
 * Db MSSQL Server Class
 */
require_once 'dbBase.php';

class dbMssql extends dbBase {
    
    
    public function init()
    {
        $this->connect();
    }
    
    /*
     * Connect to Database
     */
    protected function connect()
    {
 
        $this->connection = mssql_connect(
                $this->config['host'],
                $this->config['username'],
                $this->config['password']
                );
        
       
        if($this->connection == false){
           throw new Exception(mysql_error());
        }
        
       
        if(@mssql_select_db($this->config['dbname'], $this->connection) == false) {
            
        }
        
       
    }
    
    
    public function query($sql) {
            $resource = mssql_query($sql, $this->connection);

            if ($resource) {
                if (is_resource($resource)) {
                    $i = 0;

                    $data = array();

                    while ($result = mssql_fetch_assoc($resource)) {
                            $data[$i] = $result;

                            $i++;
                    }

                    mssql_free_result($resource);

                    $query = new stdClass();
                    $query->row = isset($data[0]) ? $data[0] : array();
                    $query->rows = $data;
                    $query->num_rows = $i;

                    unset($data);

                    return $query;	
                } else {
                        return true;
                }
            } else {
                    trigger_error('Error: ' . mssql_get_last_message($this->connection) . '<br />' . $sql);
                    exit();
            }
    }

    
    public function query_array($query){
        $rs = mssql_query($query, $this->connection);
        
        return $rs;
    }
    
    public function query_object($query){
        $rs = mssql_query($query, $this->connection);
        return mssql_fetch_object($rs);
    }
    
   
    public function escape($value) {
            $unpacked = unpack('H*hex', $value);

            return '0x' . $unpacked['hex'];
    }
	
        
    public function countAffected() {
        return mssql_rows_affected($this->connection);
    }

   public function getLastId() {
            $last_id = false;

            $resource = mssql_query("SELECT @@identity AS id", $this->link);

            if ($row === mssql_fetch_row($resource)) {
                    $last_id = trim($row[0]);
            }

            mssql_free_result($resource);

            return $last_id;
    }	
    
    
    /**
    * Begin a transaction.
    */
    public function beginTransaction()
    {
        $this->connect();
        @mssql_query("BEGIN TRANSACTION");
        return true;
    }

    /**
    * Commit a transaction.
    */
    public function commit()
    {
        @mssql_query("COMMIT TRANSACTION"); 
        return true;
    }

    /**
    * Roll-back a transaction.
    */
    public function rollBack()
    {
        @mssql_query("ROLLBACK TRANSACTION"); 
        return true;
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
            mssql_close($this->connection);
            $this->connection = null;
        }
    }
    
    public function version()
    {
        $version = mssql_query('SELECT @@VERSION');
        $row = mssql_fetch_array($version);

        echo $row[0];
    }
    
    public function queryProcedure($prName,$mssql_binds = array()){
        $result = mssql_query("SET ANSI_NULLS ON");
        $result = mssql_query("SET ANSI_WARNINGS ON");
        $query = mssql_init($prName, $this->connection) or die(mssql_get_last_message());
        
        /*
         * array @prvar,@prval,@sqltype
         */
        foreach($mssql_binds as $rs){
            mssql_bind($query, $rs['prvar'], $rs['prval'],$rs['sqltype']);
        }
//        mssql_bind($query, "@id",               $id,              SQLVARCHAR,    FALSE);
//        mssql_bind($query, "@anInt",        $anInt,       SQLINT1,    FALSE);
//        mssql_bind($query, "@someText",      $someText,     SQLTEXT,    FALSE);
        $result = mssql_execute($query) or die(mssql_get_last_message());
        
        return $result;
    }
}

?>
