Feature: Add and edit people on a family tree
    In order to model family relationships,
    As a genealogist,
    I want to add people to a family tree, defining their relationships.

Scenario: Add a single person
    Given that my tree is empty
    When I add a person called 'Bob'
    Then my tree contains 1 person
    And that person has no parents, partners, or children

Scenario: Add parents to a person
    Given that I have a tree with one person called 'Bob'
    When I add a parent called 'Jim'
    And another parent called 'Jane'
    Then Bob's parents are called
    """
    Jim
    Jane
    """

Scenario: Two people who are parents of the same child are each others partners
    Given that I have a person with a child
    And another person with no relations
    When I add the latter as a parent to the child
    Then the two parents are each other's partners
