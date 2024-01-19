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
        $password = "<your-password>";

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

    // ==================== DELETE Department data
    public function delete_department_by_id($department_id) {
        $query = "DELETE FROM department WHERE department_id = $department_id;";
        $result = $this->db_conn->query($query);

        $deleted_rows = pg_affected_rows($result);
        echo "=========================================\n";
        if ($deleted_rows) {
            echo "$deleted_rows record(s) deleted successfully!\n";
        } else {
            echo "No record found for Department ID: $department_id\n";
        }
    }

    public function delete_department_by_name($department_name) {
        $query = "DELETE FROM department WHERE department_name = '$department_name';";
        $result = $this->db_conn->query($query);

        $deleted_rows = pg_affected_rows($result);
        echo "=========================================\n";
        if ($deleted_rows) {
            echo "$deleted_rows record(s) deleted successfully!\n";
        } else {
            echo "No record found for Department Name: $department_name\n";
        }
    }

    public function delete_all_departments() {
        $query = "TRUNCATE department CASCADE;";
        $result = $this->db_conn->query($query);

        echo "=========================================\n";
        if ($result) {
            echo "Department Data Truncated!\n";
        }
    }
    // =========================================

    // ==================== UPDATE Department data
    public function update_department_name($department_name, $department_id) {
        $query = "UPDATE department SET department_name = '$department_name' WHERE department_id = $department_id;";
        $result = $this->db_conn->query($query);

        $affected_rows = pg_affected_rows($result);
        echo "=========================================\n";
        if ($affected_rows) {
            echo "$affected_rows record(s) updated successfully!\n";
        } else {
            echo "No record found for Department ID: $department_id\n";
        }
    }

    public function update_department_name_by_name($department_name, $department_name_condition) {
        $query = "UPDATE department SET department_name = '$department_name' WHERE department_name = '$department_name_condition';";
        $result = $this->db_conn->query($query);

        $affected_rows = pg_affected_rows($result);
        echo "=========================================\n";
        if ($affected_rows) {
            echo "$affected_rows record(s) updated successfully!\n";
        } else {
            echo "No record found for Department Name: $department_name_condition\n";
        }
    }
    // =========================================

    // ==================== CREATE Department data
    public function create_department($department_name) {
        $query = "INSERT INTO department (department_name) VALUES ('Admin')";
        $result = $this->db_conn->query($query);

        $affected_rows = pg_affected_rows($result);
        echo "=========================================\n";
        if ($affected_rows) {
            echo "$affected_rows record(s) created successfully!\n";
        } else {
            echo "Error in creating department!\n";
        }
    }
    // =========================================
}

$a = new Department();

// $a->update_department_name("Administrator", 23);
// $a->update_department_name_by_name("Sales", "Sales & Reach");

// $a->delete_department_by_id(30);
// $a->delete_department_by_name("Admin");
// $a->delete_all_departments();

// $a->get_department_by_id(23);
// $a->get_department_by_id(2);
// $a->get_department_by_name("Legal");
$a->get_all_departents();
?>
