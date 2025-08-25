# demoinfo

This project is teaching project. It is not meant to be example for
safe, nor useful Internet application. The purpose is to demonstrate
how simple Info-TV application might look like.

The architecture is simplified, the actual use case is following:
## Sub module
The platform provides simple API, which allows the sub module to register to server. Student implements a simple application, which retrieves data, does simple data handling and visualizes the data. This implementation is done as single PHP module (aka sub module).

Sub module is installed (copied) into `apps` folder. In this version the sub module requires registration to be carried out manually by modifying the `lib/pages.php` -file. There should be entry for each module.

## InfoTV service
The InfoTV service is just `index.php` -page. There are two use cases:
   1 it is called (GET/POST) without parameters. In this case the first sub module from registered apps is shown.
   2 it is called with parameter `next`, which contains the `uuid` of sub module to be shown.

The service will return the rendered HTML page with auto-refresh to next sub module, via `next` id. So, this `index.php` -page can be set as default page for web browser, and browser rotates the array of sub modules. When it arrives to last sub module, it will be started from first again.

# To begin

Run `composer refresh`

# Lint

There is default configuration for PHPStan in place, ie.:

```
./vendor/bin/phpstan analyse
```

# To real application

TODO List, when implementing as real application:
   * Security
      * Check user input; ie. validate the sub modules
	  * Serve pages as read-only
   * Add real database for sub module registration &amp; handling
   * Add real database for sub modules to store their data
   * Platform selection &amp; installation (e.g. Raspberry Pi)
   * Implement API for modules
   * Error checking; if module contains error its output should be discarded. This means modules should be evaluated and the evaluation value should be checked.
   * Implement graphics and Javascript support for modules
   * Add Graphics lib (plotting)
   * Give all variables default values, where appropriate
   * Check all function parameters
   * Write test cases for all functionality

