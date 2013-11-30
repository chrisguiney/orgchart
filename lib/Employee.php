<?php
class Employee {
    /**
     * @var int $id
     * Employee ID, primary key
     */
    public $id;

    /**
     * @var string $name
     * Name of employee
     */
    public $name;

    /**
     * @var int $bossId
     * Primary key of direct supervisor
     */
    public $bossId;

    /**
     * @var string $bossName
     * Name of direct supervisor
     */
    public $bossName;

    /**
     * @var int $distanceToCeo
     * Number of edges between employee and CEO
     */
    protected $distanceToCeo = null;


    /**
     * @var int $numSubordinates
     * Number of people who report to this employee
     */
    protected $numSubordinates = null;

    /**
     * @return int
     */
    public function getBossId() {
        return (int) $this->bossId;
    }

    /**
     * @return int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * @return string
     * Returns the name of the Employee
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets bossName
     * @return string
     */
    public function getBossName() {
        return $this->bossName;
    }

    /**
     * Sets boss name
     * @param string $bossName
     */
    public function setBossName($bossName) {
        $this->bossName = $bossName;
    }

    /**
     * @return int|null
     * Returns the distance to the CEO or null if not yet determined
     */
    public function getDistanceToCeo() {
        return $this->distanceToCeo;
    }

    /**
     * @param int $distance
     * Set the distance to the CEO
     */
    public function setDistanceToCeo($distance) {
        $this->distanceToCeo = $distance;
    }

    /**
     * @return int|null
     * Get the number of employees this employee is responsible for
     */
    public function getNumberOfSubordinates() {
        return $this->numSubordinates;
    }

    /**
     * @param $numSubordinates
     * Set the number of subordinates this employee is responsible for
     */
    public function setNumberOfSubordinates($numSubordinates) {
        $this->numSubordinates = $numSubordinates;
    }

    /**
     * Return an array of [name, bossName, distanceToCeo, numberOfSubordinates]
     * @return array
     */
    public function toArray() {
        return array($this->getName(), $this->getBossName(),
                     $this->getDistanceToCeo(), $this->getNumberOfSubordinates()
        );
    }

}