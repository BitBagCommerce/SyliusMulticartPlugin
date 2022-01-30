@multicart
Feature: Creating new cart
    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "shop@example.com"

    @api
    Scenario: Creating new cart as logged user
        Given I am logged in as "shop@example.com"
        And the customer has created empty cart
        When User creates new cart for current locale code
        Then User should have "2" carts

