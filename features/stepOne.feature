Feature: stepOne
  In order to book a ticket
  As a customer
  I need to be able to pick a date, a ticket type, and to give an email address

  Scenario: stepOne form display
    Given I am on "/billetterie"
    Then I should see "Date de la visite"
    And I should see "Type de billet"
    And I should see "Votre adresse e-mail"
    And I should see "Valider"

  Scenario: Filling stepOne form
    Given I am on "/billetterie"
    When I select "half" from "index_type"
    And I fill in "index_email" with "test@gmail.com"
    Then the "index_type" field should contain "half"
    And the "index_email" field should contain "test@gmail.com"
