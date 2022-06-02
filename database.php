<?php
class Database {
	private $host       = "localhost";
	private $username   = "root";
	private $password   = "";
	private $database   = "newsletter";
	private $DbCon;
	
	public function __construct(){
		$con = new mysqli($this->host,$this->username,$this->password,$this->database);
		
		if($con){
            $this->DbCon = $con;
		    return ['status' => true ];
		}else{
			return ['status' => false, 'message' => 'Database connection error .' . $con->connect_error];
		}
	}

    /**
     * getWhere
     * 
     * @param   $table      string      // table name
     * @param   $where      string      // Where Clause
     * @param   $row        string      // fields
     * @param   $orderBy    string      // Order by
     * 
     * @return  $data       array
     */
    public function getWhere($table, $where = "", $row = "*", $orderBy = "")
    {
        $sql = "SELECT $row FROM $table";

        if(!empty($where))
        {
            $sql .= " WHERE $where";
        }

        if(!empty($orderBy))
        {
            $sql .= " ORDER BY $orderBy";
        }

        $result = $this->DbCon->query($sql);
        
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

     /**
     * insertData
     * 
     * @param   $table      string      // table name
     * @param   $data       string      // Where Clause
     * 
     * @return  boolean
     */
    public function insertData($table, array $data)
    {
        if(empty($data))
            return false;

        $sql = "INSERT INTO $table (";
        $sql .= implode(",", array_keys($data)) . ') VALUES ';            
        $sql .= "('" . implode("','", array_values($data)) . "')";

        $result = $this->DbCon->query($sql);
        
        if($result) {
            return true;
        }

        return false;
    }
}