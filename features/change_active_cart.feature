@multicart
Feature: Changing active cart
    In order to add products to another cart
    As a logged in user
    I want to be able to change active cart

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "shop@example.com"

    @api
    Scenario: Changing active cart as logged user
        Given I am logged in as "shop@example.com"
        And the customer has created empty cart
        And User creates new cart for current locale code
        Then User active cart number should be "1"

    @api
    Scenario: Changing active cart as logged user
        Given I am logged in as "shop@example.com"
        And the customer has created empty cart
        And User creates new cart for current locale code
        When User changes active cart to "2" cart for current locale code
        Then User active cart number should be "2"
