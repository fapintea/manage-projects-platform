# Platform for managing bachelor or dissertation projects

### Installation guide
* Install mysql following the steps described [here](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-16-04) and create a database with a name of your choice
* Install php7.0 by running command `sudo apt-get install php7.0`
* Install composer by running command `sudo apt-get install composer`
* Run `composer install` and install the missing php extensions (`sudo apt-get install php7.0-dom, php7.0-mbstring, php7.0-ldap php7.0-mysql php7.0-zip`)
* Run `composer update`
* Add a `.env` file in the root of the project by copying it from [here](https://github.com/laravel/laravel/blob/master/.env.example) and configuring the db connection
* Run `php artisan key:generate`
* LDAP server configurations have to be done in `config/adldap.php` config file
    * The basic options needed to be filled are:
        * domain_controllers
        * base_dn
        * admin_account_prefix
        * admin_account_suffix
        * admin_username
        * admin_password
* Mail server configurations have to be made in `.env` file
```
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=<username>
    MAIL_PASSWORD=<password>
    MAIL_ENCRYPTION=tls
```
* The mails are sent using a queue; for starting this queue run the following command: `nohup php artisan queue:work --daemon &`

### License

Please see the [license file]()