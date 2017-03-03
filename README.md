# _Shoe Fly_

#### _A shoe store app to practice many-to-many relationships in php; March 3, 2017_

#### By _**Keith Evans**_

## Description

_The Shoe Fly app helps users find fly shoes. Users can search by brand to see all stores that carry it, or by store to see all the brands they carry._

## Setup/Installation Requirements

* _Navigate to the desktop in your terminal using `cd desktop`_
* _Clone GitHub repository to the desktop with `git clone (GitHub repo URL)`_
* _Navigate to main project folder using `cd shoes`_
* _Run `composer install` to install all dependencies_
* _Start MAMP and go to your phpMyAdmin page_
* _Click the import tab at the top of the page and import the .sql.zip files located in the main project directory_
* _Ensure that database paths in app/app.php and tests files match your local database server_
* _Initiate a php server using `php -S localhost:8000`_
* _In your web browser, enter 'localhost:8000' in the URL bar_

_In the case of any issues importing the databses, SQL commands are included below to construct the databases required._

## Database Construction SQL

CREATE DATABASE shoes_test;
USE shoes_test;
CREATE TABLE stores (id serial PRIMARY KEY, name VARCHAR (255));
CREATE TABLE brands (id serial PRIMARY KEY, name VARCHAR (255));
CREATE TABLE brands_stores (id serial PRIMARY KEY, brand_id INT, store_id INT);
CREATE DATABASE shoes;
USE shoes;
CREATE TABLE stores (id serial PRIMARY KEY, name VARCHAR (255));
CREATE TABLE brands (id serial PRIMARY KEY, name VARCHAR (255));
CREATE TABLE brands_stores (id serial PRIMARY KEY, brand_id INT, store_id INT);

## Known Bugs

_There are currently no known bugs._

## Support and contact details

_Any questions, comments, or bug reports can be directed to the repository administrator._

## Technologies Used

_This app is built in PHP on the Silex framework. It uses Twig for templating and MySQL for database interface._

### License

*This application is under the MIT license.*

Copyright (c) 2017 **_Keith Evans_**
