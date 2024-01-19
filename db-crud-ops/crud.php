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
    }

    public function query($query) {
        $result = pg_query($this->db_conn, $query);

        if (!$result) {
            echo "An error occurred.\n";
            exit;
        }

        $arr = pg_fetch_all($result);
        if (count($arr) > 0) {
            echo count($arr) . "\n";
            print_r($arr[0]);
            echo count($arr[0]) . "\n";
        }
        // print_r($arr);
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
        echo "Connection ban gia hy!\n";
    }
}

$host = "localhost";
$port = 5432;
$db_name = "pf";
$user = "postgres";
$password = "asdf1234";

$db_con = new DBConnection($host, $port, $db_name, $user, $password);
$db_con->select_testing();
echo "===============================================\n";
// $db_con->update_testing();
// $db_con->select_testing();

// $conn_string = "host=$host
//                 port=$port
//                 dbname=$db_name
//                 user=$user
//                 password=$password";
// $db_conn = pg_connect($conn_string) or die("Could not connect!");
// echo "Connected Succesfully! ";

// pg_close($db_conn)
?>
