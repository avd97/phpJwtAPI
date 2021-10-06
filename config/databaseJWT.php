<?php


    class Database{
        private $host;
        private $dbname;
        private $user;
        private $password;

        private $conn;


        public function connect() {
            $this->host = "localhost";
            $this->dbname = "phpapidb";
            $this->user = "root";
            $this->password = "";

            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

            if($this->conn->connect_errno) {
                // print_r($this->conn->connect_error);
                exit;
            } else {
                // return $this->conn;
                // echo "Connection established";
                // print_r($this->conn);
            }
        }

    }

    // $db = new Database();

    // $db->connect();



?>