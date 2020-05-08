Laravel-WebAuthn example application
====================================

This is an example use of [asbiin/laravel-webauthn](https://github.com/asbiin/laravel-webauthn) package.

# Demo

Try this application on [this live demo app](https://laravel-webauthn-example.herokuapp.com/).

- Just register with any email
- Then add a WebAuthn key
- Next login will ask to confirm the key

*Database is drop every day on this demo instance.*


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

* Then run lyou need to point you webserver to the `public` directory. Follow instructions on the [Laravel documentation](https://laravel.com/docs/5.8/installation#configuration).
* Be aware WebAuthn protocol only works on HTTPS mode, so you will need to set your webserver with https.


# Usage

Got to `https://localhost/webauthn/register` to register a new key.


# License

Author: [Alexis Saettler](https://github.com/asbiin)

Copyright © 2019-2020.

Licensed under the MIT License. [View license](/LICENSE).
