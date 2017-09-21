<?php
error_reporting(null);

class MySQLDatabaseClass implements ISQLDatabaseClass
{

    /** Database Credentials */
    const HOST = "localhost";
    const USER = "root";
    const PASS = "";
    const DB   = "visitor";

    /**
     * Returns connection to the mysql server.
     *
     * @return mysqli instance of the mysql server.
     */
    public static function connect()
    {
        // Initialize mysqli connection.
        $conn = new mysqli(self::HOST, self::USER, self::PASS, self::DB);

        if ($conn->connect_errno) {
            die("Couldn't connect to the database - " . $conn->connect_error);
        }

        // TODO: Remove this when code are working properly.
        error_reporting(E_ALL);

        return $conn;
    }
}