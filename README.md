Laravel-WebAuthn example application
====================================

This is an example use of [asbiin/laravel-webauthn](https://github.com/asbiin/laravel-webauthn) package.


# Installation

In order to test the application, you need to:

* Clone this repository

* Install packages and configuration:
    ```sh
    composer install
    cp .env.example .env
    php artisan key:generate
    ```

* Configure database. 
You can use an sqlite database, just put `DB_CONNECTION=sqlite` in the `.env` file:
    ```sh
    sed -i 's/\(DB_CONNECTION\)=.*/\1=sqlite/' .env
    touch database/database.sqlite
    ```

* Then you need to point you webserver to the `public` directory. Follow instructions on the [Laravel documentation](https://laravel.com/docs/5.8/installation#configuration). Be aware WebAuthn will only works on HTTPS mode, so you will need to set your webserver with https.


# Usage

Got to `https://localhost/webauthn/register` to register a new key.


# License

Author: [Alexis Saettler](https://github.com/asbiin)

Copyright © 2019.

Licensed under the MIT License. [View license](/LICENSE).
