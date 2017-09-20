<?php
Class VisitorsCount{
    public $totalVisitors = 0, $uniqueVisitors = 0, $visitorsTimed = 0, $timeCount = 0;
    private $dbHost = "127.0.0.1";
    private $dbUser = "root";
    private $dbPassword = "";
    private $dbDatabase = "darkcore_cms";

    function __construct($count = 15){
        // $count => how many minutes for timed visitors by default "Visitors past 15 minutes";
        $this->set_session($count);
        $this->totalVisitors = $this->totalVisitors();
        $this->uniqueVisitors = $this->uniqueVisitors();
        $this->visitorsTimed = $this->latestVisitors();
        $this->timeCount = $count;
    }

    private function connect(){
        //Establishing connection to database
        $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbDatabase);
        if (!$con) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        return $con;
    }
    private function set_session($minutesToCount){
        $ip = $this->get_client_ip();
        $ip = md5($ip);
        $this->save_session($ip, $minutesToCount);
    }
    private function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    private function save_session($session, $minutesCounter){
        $con = $this->connect();
        $sql  ='INSERT INTO `visitors` VALUES (?, ?, 0)';
        $date = time() + ($minutesCounter * 60);
        if ($this->get_session($session) < time()) {
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param('si', $session, $date);
                $stmt->execute();
            }
        }
    }

    private function get_session($session){
        $con = $this->connect();
        $sql  ='SELECT `date` FROM `visitors` WHERE `session_token`=?';
        $date = 0;
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('s', $session);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($data = $result->fetch_assoc())
                $date = $data['date'];
            $stmt->close();
        }
        $con->close();
        return $date;
    }

    private function latestVisitors(){
        $con = $this->connect();
        $sql  ='SELECT * FROM `visitors` WHERE `date` >= ?';
        $date = time();
        $count = 0;
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('i', $date);
            $stmt->execute();
            $stmt->store_result();
            $count = $stmt->num_rows;
            $stmt->close();
        }
        $con->close();
        return $count;
    }

    private function uniqueVisitors(){
        $con = $this->connect();
        $sql  ='SELECT COUNT(*) dupes FROM `visitors` GROUP BY `session_token` HAVING dupes >= 1;';
        $count = 0;
        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
            $stmt->store_result();
            $count = $stmt->num_rows;
            $stmt->close();
        }
        $con->close();
        return $count;
    }

    private function totalVisitors(){
        $con = $this->connect();
        $sql  ='SELECT * FROM `visitors`;';
        $count = 0;
        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
            $stmt->store_result();
            $count = $stmt->num_rows;
            $stmt->close();
        }
        $con->close();
        return $count;
    }
}

$visitors = new VisitorsCount();