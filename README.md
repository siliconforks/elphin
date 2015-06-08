# elphin
Small PHP library

To run the test suite:

    phpunit

To run the database test suite, you need to create a database named `elphin` and
enter the database credentials in the file `phpunit-database.xml`.  Create the
database tables using the file `phpunit-database.sql`.  Then run:

    phpunit -c phpunit-database.xml

Coming soon: i18n, CSV, HTTP, ...
