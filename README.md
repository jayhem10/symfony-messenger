<h1 align="center">SYMFONY-MESSENGER</h1>

<p align="center"><a href="https://symfony.com" target="_blank">
    <img src="https://www.wanadev.fr/uploads/documents/cover-messenger-symfony.png">
</a></p>


Description
------------

This is a small project to try symfony-messenger.
It build with Webpack Encore.

Installation
------------

* Clone the project, open the folder and run "composer install" in terminal.
* Run "npm install"
* Run "npm run build"
* Create the database : "php bin/console doctrine:database:create"
* Create tables : "php bin/console d:s:u -f"
* Run "php -S 127.0.0.1:8000 -t public" in terminal 
* Follow this url : http://127.0.0.1:8000/

Informations complémentaires
-------------

Some commands to try messenger :
* Send messages : "php bin/console messenger:consume async -vv"
* Stop sending messages : "php bin/console messenger:stop-workers"
* Resend messages : "php bin/console messenger:failed:retry" and run "send messages command"


Documentation
-------------
* [Symfony Documentation](https://symfony.com/doc/current/components/messenger.html) - Symfony documentation.
* [Tutorial 1](https://www.youtube.com/watch?v=0BWU-liZIU4) - Tutorial by Grafikart.
* [Tutorial 2 - part 1](https://www.youtube.com/watch?v=p6hlTWyDRmE) - Tutorial by Nouvelle techno.
* [Tutorial 2 - part 2](https://www.youtube.com/watch?v=X8eNdUkLA-0) - Tutorial by Nouvelle techno.








