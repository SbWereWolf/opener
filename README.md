# Opener
Opener is opener for remote unlock doors, base on HTTP protocol.

# Installation
## Clone Opener
```
git clone https://github.com/SbWereWolf/opener.git
```
## Setup SQLite database
```
cd path/to/opener
md configuration
```
Setup  SQLite database into configuration/ catalog with filename 'latch.sqlite'
## Setup dependencies
```
cd path/to/opener
composer install
```
## Setup web public access for opener
Setup web server with virtual host for Opener

Set Directory root to path/to/opener/api
## Setup .htaccess
Configure necessary .htaccess into path/to/opener/api
# Setup Opener service
For create database tables and fill it with data use :
```
POST "web/public/path/to/opener/storage/install/"
```
For drop data use :
```
DELETE "web/public/path/to/opener/storage/dismount/"
```
## Enjoy!
# Using with Swagger
## Swagger Php
### Clone Swagger Php
```
git clone https://github.com/zircote/swagger-php.git
```
### Generate documentation (specification) in swagger format
```
php path/to/swagger-php/bin/swagger -o path/to/opener/api path/to/opener/src
```
## Swagger UI
### Clone Swagger UI
```
git clone https://github.com/swagger-api/swagger-ui.git
```
### Copy Swagger UI to opener web public catalog
```
cd path/to/swagger-ui
xcopy /y dist/ path/to/opener/api
```
### Option step:
#### Change specification source
```
notepad|nano  path/to/opener/api/dist/index.html
```
looking for text:
```
    window.onload = function() {
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        url: "https://petstore.swagger.io/v2/swagger.json",
```
Change url parameter to web/public/path/to/opener/api/swagger.json
## Run Swagger
Open with browser the address 'web/public/path/to/opener/api/dist'

If necessary, then input into text field web/public/path/to/opener/swagger.json , press 'Explore'
## Enjoy!
# Example of use
## View free room for lease
```
Request :
GET "http://local.opener/lease/actual/"

Response :
Body :
[
  {
    "finish": 0,
    "shutter-id": 1,
    "start": 0
  },
  {
    "finish": 0,
    "shutter-id": 2,
    "start": 0
  }
]
```
## Register at Opener service
```
Request :
POST "http://local.opener/user/"
body:
{
  "email": "user@server.org",
  "password": "123456"
}
Response :
Code: 201
```
## Login into Opener service
```
Request :
POST "http://local.opener/user/login/"
Body:
{
  "email": "user@server.org",
  "password": "123456"
}

Response :
Code : 201
Body :
[
  {
    "finish": 0,
    "token": "2y10GHIjbsKUiRU94RVwGhHheE8hWmAz46DlSdmWbfU6lQWSOnJ0UPC"
  }
]
```
## Take a lease
```
Request :
POST "http://local.opener/lease/"
Body :
{
  "token": "2y10GHIjbsKUiRU94RVwGhHheE8hWmAz46DlSdmWbfU6lQWSOnJ0UPC",
  "user-id": 0,
  "shutter-id": 1,
  "start": 0,
  "finish": 0,
  "occupancy-type-id": 0
}

Response :
Code : 201
```
## View available lease
```
Request :
GET "http://local.opener/lease/current/2y10GHIjbsKUiRU94RVwGhHheE8hWmAz46DlSdmWbfU6lQWSOnJ0UPC/"

Response :
Code : 200
Body :
[
  {
    "finish": 1547842735,
    "shutter-id": 1,
    "start": 1547840935
  }
]
```
## Unlock a door
```
Request :
POST "http://local.opener/unlock/"
Body :
{
  "shutter-id": 1
}

Response :
Code : 201
```
## Request unlock of the latch-device 

```
Request :
GET "http://local.opener/unlock/localhost/"

Response :
Code : 200
```
## Report successful opening of the latch
```
Request :
DELETE "http://local.opener/unlock/localhost/"

Response :
Code : 204
```
## Logout of Opener service
```
Request :
DELETE "http://local.opener/session/2y10GHIjbsKUiRU94RVwGhHheE8hWmAz46DlSdmWbfU6lQWSOnJ0UPC/"

Response :
Code : 204
```
## Option to update lease at will
```
Request :
PUT "http://local.opener/lease/"
Body :
{
  "id": 1,
  "user-id": 1,
  "shutter-id": 2,
  "start": 1546889999,
  "finish": 1647600000,
  "occupancy-type-id": 2
}

Response :
Code : 200
```
