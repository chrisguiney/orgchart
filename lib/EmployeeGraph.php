<?php
require_once 'Employee.php';
require_once 'EmployeeGateway.php';

/**
 * Class EmployeeGraph
 * Responsible for creating and analyzing the graph of employees
 *
 * Fetches all the records from the database, and uses an internal adjacency list
 * to traverse the graph, and determine the distance from the top of the graph for each node, and
 * the number of nodes underneath it.
 *
 * This is geared towards the size of the data set in mind (~20,000).  With this considered,
 * fetching all the records is conceivable, and the graph is traversed with O(N) complexity, and the data
 * is fetched with just one query.  On my macbook pro, this equates to sub-second response time.
 *
 */
class EmployeeGraph implements IteratorAggregate{

    /**
     * @var EmployeeGateway $gateway
     */
    protected $gateway;

    /**
     * Adjacency list of boss id -> list of subordinates
     * @var array $graph
     */
    protected $graph = array();

    /**
     * @var array list of employees
     */
    protected $employees = array();


    public function __construct(EmployeeGateway $gateway) {
        $this->gateway = $gateway;
        $this->loadGraph();
    }

    protected function loadGraph() {
        // Get all employees
        $this->employees = $this->gateway->getEmployees();

        foreach($this->employees as $employee) {
            // Load the employees into an adjacency list
            // of boss id -> [list of employees]
            $this->graph[$employee->getBossId()][] = $employee;
        }

        // Get the initial node to start with
        $frontier = $this->employees[0];

        // Using an associative array because key lookups
        // are much more efficient than using in_array
        $visited = array($frontier->getId() => true);
        //Start visiting the tree
        $this->visitNode($frontier, 0, $visited);
    }

    public function getEmployees() {
        return $this->employees;
    }


    /**
     * Recursively visit a node in the graph to get the subordinate count
     * as well as the distance from the ceo
     * @param Employee $employee
     * @param int $level
     * @param array $visited
     */
    protected function visitNode(Employee $employee, $level = 0, &$visited = array()) {
        $count = 0;
        $id = $employee->getId();

        $employee->setDistanceToCeo($level);

        //If they're present in the adjacency list keys, then they have subordinates
        if(isset($this->graph[$id])) {
            foreach($this->graph[$id] as $subordinate) {
                $subordinateId = $subordinate->getId();

                // If they've already been visited, don't revisit
                // as is the case with the CEO
                if(isset($visited[$subordinateId])) {
                    continue;
                }

                $count += 1;
                $visited[$subordinateId] = true;
                // Recurse to fill in the subordinate's subordinate count
                $this->visitNode($subordinate, $level + 1, $visited);
                $count += $subordinate->getNumberOfSubordinates();
            }
        }
        $employee->setNumberOfSubordinates($count);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator() {
       return new ArrayIterator($this->employees);
    }

}