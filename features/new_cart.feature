@multicart
Feature: Creating new cart
    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "shop@example.com" identified by "sylius"

    @api
    Scenario: Creating new cart as logged user
#        Given I am logged in as "shop@example.com"
        Given I am a logged in customer
#        And the customer has created empty cart
        When User creates new cart for "en_US" locale code
#        Then Cart should have been created

