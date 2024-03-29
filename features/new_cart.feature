@multicart
Feature: Creating new cart
    In order to create a new cart
    As a logged in user
    I want to be able to create new carts

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "shop@example.com"

    @api @javascript
    Scenario: Creating new cart as logged user
        Given I am logged in as "shop@example.com"
        And I am authenticated with token "TestToken"
        And the customer has created empty cart
        When User creates new cart for current locale code
        When User changes active cart to "2" cart for current locale code
        Then User active cart number should be "2"
        Then Cart should have 0 items
        Then User should have "2" carts
