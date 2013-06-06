Generator
=========

WARNING: This is super pre-alpha. Disregard everything.

Generator is a code generation tool. You write templates in PHP but the output can be in any language. It is at its most powerful when given a database schema to read. You can create ORMs, administration interfaces, full CRUD applications.

Workflow
--------

Generator is very useful when starting a new project. I maintain a bootstrap project (link) in my github repository. To begin, I clone my bootstrap repo:

> git clone https://github.com/dclaysmith/bootstrap.git

I then run Composer to download any dependencies:

> composer.phar install

After creating my initial database, I update my generator.json with the database credentials and run Generator:

> generator.phar generate

Of course database schemas tend to change. If it does, I simply re run the "generate" command. 