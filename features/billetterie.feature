Feature: Billetterie
  In order to access the booking service
  As a customer
  I need to be able to click on the link

  Scenario:
    Given I am on "/"
    When I follow "Billetterie"
    Then I should be on "/billetterie"
