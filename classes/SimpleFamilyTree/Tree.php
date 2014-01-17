<?php

namespace SimpleFamilyTree;

class Tree {

    /** @var array */
    protected $people;

    /** @var int */
    public static $latest_id = 0;

    function __construct() {
        $this->people = array();
    }

    public function add(Person $person) {
        if ($this->contains($person)) {
            return;
        }
        $person->set_id(self::$latest_id++);
        $this->people[$person->get_id()] = $person;
    }

    public function countAll() {
        return count($this->people);
    }

    public function __toString() {
        return join("\n", $this->people);
    }

    public function children_of($person) {
        echo "\n looking for children of ".$person->get_name();
        $children = array();
        foreach ($this->people as $p) {
            echo "\nChecking parents of ".$p->get_name();
            foreach ($p->get_parents() as $parent) {
                echo " - parent=".$parent->get_name();
                if ($parent->get_name() == $person->get_name()) {
                    echo " -- match!";
                    $children[] = $p;
                }
            }
        }
        echo "\nfound children = ".join(',',$children)."\n";
        return $children;
    }

    public function contains($person) {
        foreach ($this->people as $p) {
            if ($p->get_name() == $person->get_name()) {
                return true;
            }
        }
        return false;
    }

}
