<?php
class DBConnection {
    private $host;
    private $port;
    private $db_name;
    private $user;
    private $password;
    private $db_conn;

    function __construct($host, $port, $db_name, $user, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->db_name = $db_name;
        $this->user = $user;
        $this->password = $password;

        $this->connect_db();
    }

    function __destruct() {
        pg_close($this->db_conn);
        echo "=========================================\n";
        echo "DB Connection closed!\n";
    }

    public function query($query) {
        $result = pg_query($this->db_conn, $query);

        if (!$result) {
            echo "An error occurred.\n";
            return false;
        }

        return $result;
    }

    public function get_row_count($table_name) {
        $query = "SELECT COUNT(*) FROM $table_name;";
        $result = $this->db_conn->query($query);

        $res = pg_fetch_row($result);
        return (int)$res[0];
    }

    public function select_testing() {
        $this->query("SELECT * FROM department d");
    }

    public function update_testing() {
        $this->query("UPDATE department SET department_name='Production' WHERE department_id=1");
    }

    private function get_conn_string() {
        return "host=$this->host
        port=$this->port
        dbname=$this->db_name
        user=$this->user
        password=$this->password";
    }

    private function connect_db() {
        $conn_string = $this->get_conn_string();
        $this->db_conn = pg_connect($conn_string);
        echo "=========================================\n";
        echo "DB Connection Successful!\n";
    }
}

class Department {
    private $db_conn;

    function __construct() {
        $host = "localhost";
        $port = 5432;
        $db_name = "pf";
        $user = "postgres";
        $password = "asdf1234";

        $this->db_conn = new DBConnection($host, $port, $db_name, $user, $password);
    }

    // to show data in pretty format
    private function show_department_data($department_data) {
        echo "=========================================\n";
        if (count(pg_fetch_all($department_data)) == 0) {
            echo "No record found!\n";
            return;
        }
        while ($row = pg_fetch_row($department_data)) {
            echo "Department ID: $row[0]  Department Name : $row[1] \n";
        }
    }

    // ==================== READ Department data
    public function get_department_by_id(int $department_id) {
        $query = "SELECT * FROM department d WHERE department_id = $department_id;";
        $result = $this->db_conn->query($query);

        $this->show_department_data($result);
    }

    public function get_department_by_name(string $department_name) {
        $query = "SELECT * FROM department d WHERE department_name = '$department_name';";
        $result = $this->db_conn->query($query);

        $this->show_department_data($result);
    }

    public function get_all_departents() {
        $query = "SELECT * FROM department d;";
        $result = $this->db_conn->query($query);

        $this->show_department_data($result);
    }
    // =========================================
}

$a = new Department();
$a->get_department_by_id(23);
$a->get_department_by_id(2);
$a->get_department_by_name("Legal");
$a->get_all_departents();
?>
