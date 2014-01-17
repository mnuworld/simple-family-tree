<?php

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\Gherkin\Node\PyStringNode;
use SimpleFamilyTree\Tree,
    SimpleFamilyTree\Person;

require_once 'vendor/autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class TreeContext extends BehatContext {

    /** @var Node */
    protected $node;

    /** @var Graph */
    protected $tree;

    /**
     * @Given /^that my tree is empty$/
     */
    public function thatMyTreeIsEmpty() {
        $this->tree = new Tree();
        assertEquals(0, $this->tree->countAll());
    }

    /**
     * @When /^I add a person called \'([^\']*)\'$/
     */
    public function iAddAPersonCalled($name) {
        $this->person = new Person($name);
        $this->person->set_tree($this->tree);
    }

    /**
     * @Then /^my tree contains (\d+) person$/
     */
    public function myTreeContainsPerson($count) {
        assertEquals($count, $this->tree->countAll());
    }

    /**
     * @Given /^that person has no parents, partners, or children$/
     */
    public function thatPersonHasNoParentsPartnersOrChildren() {
        assertEmpty($this->person->get_parents());
        assertEmpty($this->person->get_partners());
        assertEmpty($this->person->get_children());
    }

    /**
     * @Given /^that I have a person with a child$/
     */
    public function thatIHaveAPersonWithAChild() {
        $this->parent1 = new Person("Parent One");
        $this->child = new Person("Child");
        $this->child->add_parent($this->parent1);
        assertEquals(array($this->parent1->get_id() => $this->parent1), $this->child->get_parents());
        assertEquals(array($this->child->get_id() => $this->child), $this->parent1->get_children());
    }

    /**
     * @Given /^another person with no relations$/
     */
    public function anotherPersonWithNoRelations() {
        $this->parent2 = new Person("Parent One");
        assertEmpty($this->parent2->get_parents());
        assertEmpty($this->parent2->get_partners());
        assertEmpty($this->parent2->get_children());
    }

    /**
     * @When /^I add the latter as a parent to the child$/
     */
    public function iAddTheLatterAsAParentToTheChild() {
        $this->child->add_parent($this->parent2);
    }

    /**
     * @Then /^the two parents are each other\'s partners$/
     */
    public function theTwoParentsAreEachOtherSPartners() {
        assertEquals(array($this->parent2->get_id() => $this->parent2), $this->parent1->get_partners());
        assertEquals(array($this->parent1->get_id() => $this->parent1), $this->parent2->get_partners());
    }

    /**
     * @Given /^that I have a tree with one person called \'([^\']*)\'$/
     */
    public function thatIHaveATreeWithOnePersonCalled($name) {
        $this->tree = new Tree();
        $this->person = new Person($name);
        $this->person->set_tree($this->tree);
    }

    /**
     * @When /^I add a parent called \'([^\']*)\'$/
     */
    public function iAddAParentCalled($name) {
        $this->parent = new Person($name);
        $this->parent->set_tree($this->tree);
        $this->person->add_parent($this->parent);
    }

    /**
     * @Given /^another parent called \'([^\']*)\'$/
     */
    public function anotherParentCalled($name) {
        $this->parent2 = new Person($name);
        $this->parent2->set_tree($this->tree);
        $this->person->add_parent($this->parent2);
    }

    /**
     * @Then /^Bob's parents are called$/
     */
    public function parentsAreCalled(PyStringNode $string) {
        assertCount(2, $this->person->get_parents());
        assertContains($this->parent, $this->person->get_parents());
        assertContains($this->parent2, $this->person->get_parents());
    }

    /**
     * @Then /^my graph contains$/
     */
    public function myGraphContains(PyStringNode $string) {
        // Have the same number
        assertEquals($this->tree->countAll(), count($string->getLines()));
        // And each incoming node is in the nodeList
        foreach ($string->getLines() as $l) {
            $node = new Node($l);
            assertTrue($this->tree->contains($node));
        }
    }

    /**
     * @Given /^when I count all nodes I get (\d+)$/
     */
    public function whenICountAllNodesIGet($arg1) {
        assertEquals($arg1, $this->tree->countAll());
    }

    /**
     * @When /^I add "([^"]*)" to all titles$/
     */
    public function iAddToAllTitles($arg1) {
        $this->tree->appendToTitles($arg1);
    }

    /**
     * @Then /^when I print all nodes I get \'([^\']*)\'$/
     */
    public function whenIPrintAllNodesIGet($arg1) {
        assertEquals($arg1, (string) $this->nodes);
    }

    /**
     * @Then /^the children of the first should be \'([^\']*)\'$/
     */
    public function theChildrenOfTheFirstShouldBe($arg1) {
        throw new PendingException();
    }

    /**
     * @Given /^that I create some nodes:$/
     */
    public function thatICreateSomeNodes(PyStringNode $nodeList) {
        $this->tree = new Graph();
        foreach ($nodeList->getLines() as $l) {
            $node = new Node($l);
            $node->set_graph($this->tree);
        }
    }

    /**
     * @Given /^I create a parent node$/
     */
    public function iCreateAParentNode() {
        $this->parentNode = new Node();
    }

    /**
     * @Given /^I create child node node$/
     */
    public function iCreateChildNodeNode() {
        $this->childNode = new Node();
    }

    /**
     * @Given /^add the child as a child of the parent$/
     */
    public function addTheChildAsAChildOfTheParent() {
        $this->parentNode->add_child($this->childNode);
    }

    /**
     * @Then /^the children of the parent should be the child$/
     */
    public function theChildrenOfTheParentShouldBeTheChild() {
        $children = array($this->childNode->get_id() => $this->childNode);
        assertEquals($children, $this->parentNode->get_children());
    }

    /**
     * @Given /^the parents of the child should be the parent$/
     */
    public function theParentsOfTheChildShouldBeTheParent() {
        $parents = array($this->parentNode->get_id() => $this->parentNode);
        assertEquals($parents, $this->childNode->get_parents());
    }

}
