Feature: Test WikiPedia
  Scenario: Hello World
    Given I am on "http://en.wikipedia.org/wiki/Main_Page"
    Then I should see "Wiki"