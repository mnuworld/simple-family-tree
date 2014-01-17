<?php

namespace SimpleFamilyTree;

class Person {

    private $id;

    /** @var string */
    private $name;

    /** @var array */
    private $parents;

    /** @var array */
    private $partners;

    /** @var Tree */
    private $tree;

    public function __construct($name = FALSE) {
        $this->parents = array();
        $this->partners = array();
        $this->set_tree(new Tree());
        if ($name) {
            $this->set_name($name);
        }
    }

    public function set_tree($tree) {
        $this->tree = $tree;
        $this->tree->add($this);
    }

    public function get_tree() {
        return $this->tree;
    }

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function add_parent($parent) {
        $this->parents[$parent->get_id()] = $parent;
        echo "\n** adding parent '$parent' to $this. children of $parent = ".join(', ', $parent->get_children());
        if (!in_array($this, $parent->get_children())) {
            echo "**\n adding child '$this' to $parent.";
            $parent->add_child($this);
        }
    }

    public function add_partner($partner) {
        $this->partners[$partner->get_id()] = $partner;
        if (!in_array($this, $partner->get_partners())) {
            $partner->add_partner($this);
        }
    }

    public function add_child($child) {
        if (!in_array($this, $child->get_parents())) {
            $child->add_parent($this);
        }
    }

    public function get_parents() {
        return $this->parents;
    }

    public function get_partners() {
        return $this->partners;
    }

    public function get_children() {
        return $this->tree->children_of($this);
    }

    public function set_name($name) {
        $this->name = $name;
    }

    public function get_name() {
        return $this->name;
    }

    public function __toString() {
        return $this->get_name();
    }

}
