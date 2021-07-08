
# Simple MP3 Player

Play song, add playlist and more!

## Requirements

* Composer
* Node
* MySQL

## Installation
Use the package manager npm and composer to setup the project. 

###### Database Setup
Create an empty database `mp3`.

###### UI Installation
```bash
cd ui
npm install
```

###### API Installation
```bash
cd api
composer update
composer install
php artisan storage:link
php artisan migrate
```
