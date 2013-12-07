Generator - Very Pre-Alpha Proceed with Caution
===============================================

[!["Travis CI Build Status"][2]][1]

  [1]: https://travis-ci.org/dclaysmith/generator
  [2]: https://api.travis-ci.org/dclaysmith/generator.png

![Codeship CI Status](https://www.codeship.io/projects/7ffe7a70-418f-0131-286b-0a3efd36b955/status "Codeship CI Status")

Generator is a code generation tool written in PHP. You write templates in PHP but the output can be in any language you desire. You can create ORM layers, administration interfaces, or full CRUD applications.

Generator currently supports MySql but in time should support a number of different relational datasources.

Installation
------------

To install Generator, run the following command from the directory you would like to install Generator:

`curl -sSL https://github.com/dclaysmith/generator/releases/v0.1.1/2843/installer | php`

Or download `generator.phar` from the most current release and run:

`php generator.phar`

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
