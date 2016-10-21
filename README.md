# Book Ocean Crest
----

This is one of my first web apps. I want to refactor it.

## Refactoring Steps

* Implement Autoloading
* Consolidate Classes and Functions
* Replace global with Dependency Injection
* Replace new with Dependency Injection
* Write Tests
* Extract SQL Statments to Gateways
* Extract Domain Logic to Transactions
** normalizing, filtering, sanitizing, and validating of data
** calculation, modification, creation, and manipulation of data
** sequential or concurrent operations and actions using the data
** retention of success/failure/warning/notice messages from those operations and actions 
** retention of values and variables for later inputs and outputs
* Extract Presentation Logic to View Files
* Extract Action Logic to Controllers
* Replace inlcludes in Classes
* Separate Public And Non-Public Resources
* Decouple url paths from file paths
* Remove Repeated Logic In Page Scripts
* Add a Dependency Injection Container

# Local Test Server
* ```php -S localhost:8000```
* ```mysql.server start```
* ```mysql.server stop```

## Database Setup
You'll need to rename ```/config/example.database.php``` to just ```database.php```

```
CREATE TABLE `ocUsers` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `side` varchar(25) NOT NULL DEFAULT '',
  `activated` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
```

```
CREATE TABLE `ocCalendar` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `family` varchar(250) NOT NULL DEFAULT '',
  `event` varchar(250) NOT NULL DEFAULT '',
  `dateField` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2960 DEFAULT CHARSET=latin1;
```
