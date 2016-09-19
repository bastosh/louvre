Feature: stepTwo
  In order to add tickets to my order
  As a customer
  I need to be able to add visitors

  Scenario: stepTwo form display
    Given I am on "/order/1"
    Then I should see "Ajouter un visiteur"
    And I should see "Valider"

  Scenario: add another visitor button display
    Given I am on "/checkout/1"
    Then I should see "Ajouter un autre billet"

  Scenario: add another visitor from checkout
    Given I am on "/checkout/1"
    When I follow "Ajouter un autre billet"
    Then I should be on "/order/1"