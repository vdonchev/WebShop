WebShop - a Symfony 3 project
-----------------------------

Simple web-shop application build with 
**Symfony** framework.
Project created as a practical exam work for 
**PHP MVC Frameworks**
course@***Softuni*** (2017-04)

---

Author:
-------
Videlin Donchev (vdonchev) www.videlin.eu

Powered by:
* Symfony 3
* MySQL
* Bootstrap 3

---

Installation on Windows:
------------------------
Requirements: **composer**, **MySql server**

1. Download/Clone source from this repository
2. `cd` into the project folder
3. Execute commands: 
    1. `composer install`
    2. `php bin/console doctrine:database:create`
    3. `php bin/console doctrine:migrations:migrate`
    4. `php bin/console doctrine:fixtures:load`
    5. `php bin/console server:run`
4. Open `http://127.0.0.1:8000` in your browser.
5. Account login details:
    * Admin account: `admin@videlin.eu`, pass: `123`
    * Editor account: `editor@videlin.eu`, pass: `123`
    
---

Live demo:
----------
https://symfony.videlin.eu