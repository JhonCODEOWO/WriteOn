# WriteOn ![Status](https://img.shields.io/badge/status-in%20progress-yellow)
WriteOn is a OpenSource project API which you can create notes and share them with people working on a live environment.

## 🚧 Project status

This project still in development all code are subject to changes and some features are 
- Core features almost done (Auth, Notes)
- Share and collaborative features **in progress**

## General notes
Be sure to install before implentation of this project the next things
 - PHP 8.0 or newer
 - Composer
 - NodeJS
 - Mysql
 - Server Apache or Nginx
 - Laravel 10 or newer

If you want use this project as a implementation or developing purposes you need to complete this steps:

 1. Clone the repository using `git clone`
 2. Execute `npm install` and `composer install` to install all necessary dependencies of the application.
 3. Copy the .env file and place in it the credentials for your database engine
 4. Be sure to write all credentials to use websockets work inside the .env file
 5. If you're in **local** or **production** environment be sure to write it in **APP_ENV** inside .env file, some seed data are created based in the word wrote inside that key.
 6. Execute command `php artisan key:generate`

## Installation notes
First you should cloned this project or download all the files inside your server and complete all requirements in global notes then you should complete the next steps: 
 1. Use the commands `php artisan migrate --seed`and `php artisan serve`.
 2. Execute `npm run dev`, `php artisan reverb:start`
 3. Read all docs of the endpoints to implement this backend in your own applications.

## 📄 License
This project is open-source and available under the MIT license.