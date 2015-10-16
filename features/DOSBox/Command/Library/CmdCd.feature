Feature: Change directory
  As a system directory
  I want to change to another directory

  Scenario: change to sub directory
    Given drive C have files and dirs
     When I change to sub directory
     Then I should be in the sub directory