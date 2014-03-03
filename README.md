behat_editor_core
=================

Library for behat_editor to work in a different number of frameworks

This will bring together some common features to make the BehatEditor work

Writing Tests
Reading Tests
Running Tests
Updating behat.yml files as needed
Hookable for Reporting and other plugins

Roadmap
-------
3-15-2014 
release of core features to full using in Laravel and Drupal.
All those frameworks need to is

  * Load in library via composer 
  * Make rest endpoints or other routes to call to the related controller
  * Interact with the library to get and set behat.yml files, tests and actions like "run test"
