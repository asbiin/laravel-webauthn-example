Laravel-WebAuthn example application
====================================

This is an example use of [asbiin/laravel-webauthn](https://github.com/asbiin/laravel-webauthn) package.

# Demo

Try this application on [this live demo app](https://laravel-webauthn-example.herokuapp.com/).

- Just register with any email
- Then add a WebAuthn key
- Next login will ask to confirm the key

**Accounts are automatically deleted after 24h on this demo instance.**

## Deploy on heroku

You can also [![deploy it to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/asbiin/laravel-webauthn-example/tree/main)


# Installation

In order to test the application, you need to:

* Clone this repository

* Install packages and configuration:
    ```sh
    composer install
    npm install
    npm prod
    cp .env.example .env
    php artisan key:generate
    ```

* Configure database. 
You can use an sqlite database, just put `DB_CONNECTION=sqlite` in the `.env` file:
    ```sh
    sed -i 's/\(DB_CONNECTION\)=.*/\1=sqlite/' .env
    touch database/database.sqlite
    ```

* Run a webserver
  -  You'll need to point you webserver to the `public` directory. Follow instructions on the [Laravel documentation](https://laravel.com/docs/installation#configuration).

  - WebAuthn protocol requires HTTPS mode, and forbid the `localhost` url.
  - Use laravel serve command, and a tool like [ngrok](https://ngrok.com/):
     1. `php artisan serve`
     2. `ngrok http http://127.0.0.1:8000`
     3. you can use the `https` version of the `ngrok` output


# Usage

Got to `https://url.test/webauthn/register` to register a new key.


# License

Author: [Alexis Saettler](https://github.com/asbiin)

Copyright © 2019–2022.

Licensed under the MIT License. [View license](/LICENSE).
