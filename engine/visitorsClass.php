<?php

Class VisitorsCount
{

    private $visitor_handler;

    /**
     * VisitorsCount constructor.
     */
    public function __construct()
    {
        $this->visitor_handler = new VisitorDaoClass();
    }

    public function set_session($minutesToCount)
    {
        $ip = $this->get_client_ip();
        $ip = md5($ip);
        $this->save_session($ip, $minutesToCount);
    }

    private function save_session($session, $minutesCounter)
    {
        $this->visitor_handler->save_session($session, $minutesCounter);
    }

    private function get_session($session)
    {
        return $this->visitor_handler->get_session($session);
    }

    public function latestVisitors()
    {
        return $this->visitor_handler->latest_visitors();
    }

    public function uniqueVisitors()
    {
        return $this->visitor_handler->unique_visitors();
    }

    public function totalVisitors()
    {
        return $this->visitor_handler->total_visitors();
    }

    public function start_time($time)
    {
        return $time;
    }

    private function get_client_ip()
    {
        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            return getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            return getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            return getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            return getenv('REMOTE_ADDR');
        else
            return "UNKNOWN";
    }

}

$visitors = new VisitorsCount();
$visitors->set_session(15);