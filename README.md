Generator
=========

Generator is a code generation tool written in PHP. You write templates in PHP but the output can be in any language you desire. It is at its most powerful when given a database schema to read. You can create ORM layers, administration interfaces, or full CRUD applications.

Generator currently supports MySql but in time should support a number of different relational database datasources.

Installation
------------

NOTE: Disregard this method, the installer executable is not included in that release. Download `generator.phar` from the most current release. 

`curl -sS https://github.com/dclaysmith/generator/releases/v0.1.0/2645/installer | php`

Usage
-----

Run Generator:

```
$ php generator.phar
```


Workflow
--------

Generator is very useful when starting a new project. I maintain a bootstrap project (link) in my github repository. To begin, I clone my bootstrap repo:

`git clone https://github.com/dclaysmith/bootstrap.git`

I then run [Composer](https://getcomposer.org) to download any dependencies:

`composer.phar install`

After creating my initial database, I update my generator.json with the database credentials and run Generator:

`generator.phar`

Of course database schemas tend to change. If it does, I simply re run the "generate" command. 