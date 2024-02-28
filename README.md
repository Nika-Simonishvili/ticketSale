## This is API for ticketsSale application with Laravel.

In order to run project locally, please do as following:

* Clone project.
* Go to the folder application using cd command on your cmd or terminal
* Run composer install on your cmd or terminal
* Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal, Ubuntu
*  Copy .env.example file to .env.testing on the root folder. 
* Open your .env files and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration. Use different database for testing.
* Run php artisan key:generate
* Run php artisan migrate --seed
* Run php artisan serve

To run tests, make sure you have proper .env.testing file.
in your command line type:
* composer test
