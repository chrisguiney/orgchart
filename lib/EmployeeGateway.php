<?php
require_once 'Employee.php';

/**
 * Class EmployeeGateway
 * Responsible for all the database interaction with regards to
 * the employee table.
 */
class EmployeeGateway {

    /**
     * @var PDO
     */
    protected $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @return array of Employees
     */
    public function getEmployees(){
        $stmt = $this->connection->prepare("
          SELECT
              employees.*,
              bosses.name as bossName
          FROM employees
          JOIN employees bosses ON employees.bossId = bosses.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Employee");
    }
}