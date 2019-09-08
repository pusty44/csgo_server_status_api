# CSGO SERVER STATUS API

App used to refresh server data and put it into database

## Installation

Use the package manager [composer](https://getcomposer.org/) to install all dependencies.
You need php7.1 and composer installed as system path.

```bash
composer install
php -S 127.0.0.1:8000 -t public #in main folder of repository to start a preview
```
To use it on dedicated server you need to park domain to ./public folder 

## Available routes
```/v1/server/add (POST) PARAMS: host,port,token``` - route to add new server to api

```/v1/server/delete (POST) PARAMS: host, port, token``` - route to delete server from api

```/v1/servers/update (POST/GET) NO PARAMS``` - route to refresh servers data

```/v1/server/info (POST) PARAMS: host, port``` - route to get one server data

```/v1/servers/info (GET) NO PARAMS``` - route to get all servers data

```/v1/steam/group (GET) NO PARAMS``` - route to get all params of steam group e.g. members count, members in game, members online, members list
