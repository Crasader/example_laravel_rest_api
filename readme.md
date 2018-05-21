# Laravel REST API example
This example is an application for creating user's PDFs. There are three test types of PDFs in the system:

  - Short
  - Full
  - Advanced
 
The application provides REST API for manipulating PDF resource. You can get all three types list of user's PDFs, create PDFs (render a PDF from [Blade](https://laravel.com/docs/5.6/blade) template using [Dompdf](https://github.com/dompdf/dompdf) package and save neccessary information to the database), get/update/delete a PDF by the requested id. The app uses [jwt-auth](https://github.com/tymondesigns/jwt-auth) package for provide user authentication.

This repository is connected to [Vue.js PDF Manager](https://github.com/akozyr/example_vue_client).

### Installation
The app requires PHP 7.1+ to run. 

Install the dependencies and enjoy!

```
cd example_laravel_rest_api
composer update
```

### 3rd-party packages
The app is currently extended with the following packages. 

| Packages | Repositories |
| ------ | ------ |
| Dompdf | https://github.com/dompdf/dompdf |
|  DOMPDF Wrapper for Laravel | https://github.com/barryvdh/laravel-dompdf |
| Laravel 5 - Repositories to the database layer | https://github.com/andersao/l5-repository |
| JSON Web Token Authentication for Laravel & Lumen | https://github.com/tymondesigns/jwt-auth |

### Testing
The app includes two types of testing: unit tests and integration tests.

You can run integration tests using PHPUnit installed in the Laraver vendor folder:
```
cd example_laravel_rest_api
./vendor/bin/phpunit --testsuite=api
```
For running unit tests, please use a next command in the same folder `./vendor/bin/phpunit --testsuite=unit`. Also you can run all existing tests using `./vendor/bin/phpunit --testsuite=all`.

### License
MIT

