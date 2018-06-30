**Application Journey**

Installed and configured Vagrant BOX CentOS7 over VirtualBox
Started symfony project from scratch

Inside Vagrant
Installed php7.2
Installed composer and project dependencies
Configured vagrant to execute symfony server
Installed mariadb as database (librarian / 123456)

Currency guessed in euros
Database is not secured, neither privileges are well specified
Could not make it work with yaml entities

create method

Item creation
- Create a new item with the specified data
- Path: /api/v1/items
- HTTP Method: POST

Request parameters:
image
title
author
price

Response Object
BookDetailItem
id
image
title
author
price

Example
...