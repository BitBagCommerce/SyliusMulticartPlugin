@api
Feature: Creating new cart
    Background:
        Given the store operates on a single channel in "United States"

    Scenario: Creating new cart as logged user
        Given I am logged in as "shop@example.com"

