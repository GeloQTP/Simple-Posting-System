<?php

class DashboardStats
{
    private mysqli $conn; // private property (variable) only accesible to this class

    public function __construct(mysqli $conn) // will be automatically called when we create an object
    {
        $this->conn = $conn;
    }

    public function totalUsers(): int // : int indicates 
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM users");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    public function totalAdmins(): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM users WHERE userType='admin'");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    public function newUsersToday(): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM users WHERE DATE(reg_date) = CURDATE()");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    public function newUsersThisMonth(): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM users WHERE MONTH(reg_date) = MONTH(CURDATE()) AND YEAR(reg_date) = YEAR(CURDATE())");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}
