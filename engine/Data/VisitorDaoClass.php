<?php

class VisitorDaoClass
{

    public function save_session($session, $minutesCounter)
    {
        $con = MySQLDatabaseClass::connect();
        $sql = 'INSERT INTO `visitors` VALUES (?, ?, 0)';
        $date = time() + ($minutesCounter * 60);
        if ($this->get_session($session) < time()) {
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param('si', $session, $date);
                $stmt->execute();
            }
        }
    }

    public function get_session($session)
    {
        $con = MySQLDatabaseClass::connect();
        $sql = 'SELECT `date` FROM `visitors` WHERE `session_token`=?';
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

    public function latest_visitors()
    {
        $con = MySQLDatabaseClass::connect();
        $sql = 'SELECT * FROM `visitors` WHERE `date` >= ?';
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

    public function unique_visitors()
    {
        $con = MySQLDatabaseClass::connect();
        $sql = 'SELECT COUNT(*) dupes FROM `visitors` GROUP BY `session_token` HAVING dupes >= 1;';
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

    public function total_visitors()
    {
        $con = MySQLDatabaseClass::connect();
        $sql = 'SELECT * FROM `visitors`;';
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