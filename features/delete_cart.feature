@multicart
Feature: Creating new cart
#    Background:
#        Given the store operates on a single channel in "United States"

    Scenario: Creating new cart as logged user
#        Given the store operates on a single channel in "United States"
        Given I am logged in as "shop@example.com"
        Given I have ca
        When User creates new cart for "en_US" locale code
#        Then Cart should have been created

