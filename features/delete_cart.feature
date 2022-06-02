@multicart
Feature: Removing a cart
    In order to remove redundant cart
    As a logged in user
    I want to be able to delete cart

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "shop@example.com"

    @api
    Scenario: Deleting cart as logged user
        Given I am logged in as "shop@example.com"
        And the customer has created empty cart
        And User creates new cart for current locale code
        When User deletes "2" cart for current locale code
        Then User should have "1" carts
        Then total order items should be 0
