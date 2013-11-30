<?php
// Modify these as needed to connect to your database
define("DB_DSN", "mysql:dbname=orgchart;host=localhost");
define("DB_USER", "orgchart");
define("DB_PASSWORD", "testpass");

require_once "../lib/Employee.php";
require_once "../lib/EmployeeGateway.php";
require_once "../lib/EmployeeGraph.php";

// Setup error handling

// Use syslog for error logging, because if your op team is unhappy,
// you"re gonna have a bad time

// LOG_PERROR will send the log messages to STDERR as well -- useful
// if you"re using this from the command line.
openlog("orgchart", LOG_PERROR & LOG_ODELAY, LOG_LOCAL0);

function errorResponse($error) {
    header("Content-Type", "application/json");
    echo json_encode(array("error" => $error));
    exit();
}

set_error_handler(function($errno, $errstr) {
    syslog(LOG_ERR, "{$errno}: {$errstr}");
    errorResponse($errstr);
});

set_exception_handler(function(Exception $e) {
    syslog(LOG_ERR, "{$e->getMessage()}");
    errorResponse($e->getMessage());
});


//Inject dependencies
$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$employeeGateway = new EmployeeGateway($db);
$employeeGraph = new EmployeeGraph($employeeGateway);

// Transform the employee list to be friendly to datatables
$employeeList = array_map(function($employee) {
    return $employee->toArray();
}, $employeeGraph->getEmployees());

header("Content-Type", "application/json");
echo json_encode(array("aaData" => $employeeList));
exit();