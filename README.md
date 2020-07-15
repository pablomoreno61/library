**First steps**

I've developed the server-side implementation using Symfony 4 framework, which was unknown
for me until this moment, and it has been a real pleasure to play with it. 

The project has been developed and tested under a VagrantBox containing CentOS7 over VirtualBox (https://app.vagrantup.com/centos/boxes/7).
I've installed via yum repositories PHP7.2 and MariaDB for database storage. As it is not a production application
I've used the symfony server, which starts when VM starts, listening on **http://192.168.33.10:8000** url.

From a starting point, I've used the Symfony skeleton with Doctrine support as ORM, and code has 
been developed following clean code guidelines in order to be rigorous with the code styling and an hexagonal
architecture for class directories. I've used Composer for third party librarys, unit tests for testing and git for VCS.

Under src directory, you can find a domain called Library with three different layers:
- Application: It contains all the endpoints to the API RESTful, including controllers, use cases, frontend exceptions and json objects
- Domain: Business logic stuff is contained here, entities and repository interfaces. At this moment I didn't considered
any other core rule or domain service that should be integrated here
- Infrastructure: Doctrine repositories and database entity configurations (unused)

Database has been created directly using MariaDB cli, including a user to be accessed from our application. Database structure has been
created using doctrine migrations.

List items endpoint follows the hateoas to API navigation, so after listing the available books, you can find its detail book link
in every returned document.

**Create Item**

- Create a new item with the specified data
- Path: /api/v1/items
- HTTP Method: POST

Request parameters

| Name   | Description                   | Type        | Comment             | Example value                                                                                    |
|--------|-------------------------------|-------------|---------------------|------------------------------------------------------------------------------------------|
| title  | The title of the book         | String(50)  | Mandatory parameter | Lord of the Rings                                                                        |
| price  | Price in euros of the book    | Double      | Mandatory parameter | 76.56                                                                                    |
| image  | The url to the item cover-art | String(100) | Mandatory parameter | https://images-na.ssl-images-amazon.com/images/I/51EstVXM1UL._SX331_BO1,204,203,200_.jpg |
| author | The author of the book        | String(50)  | Mandatory parameter | JRR Tolkien                                                                              |


Response Object

BookItemDetail

| Attribute name | Description                   | Type        | Example value                                                                            |
|----------------|-------------------------------|-------------|------------------------------------------------------------------------------------------|
| id             | Unique identifier of the book | String(36)  | 07ed651f-500c-45d1-99a4-65fbaf302494                                                     |
| title          | The title of the book         | String(50)  | Lord of the Rings                                                                        |
| price          | Price in euros of the book    | Double      | 76.56                                                                                    |
| image          | The url to the item cover-art | String(100) | https://images-na.ssl-images-amazon.com/images/I/51EstVXM1UL._SX331_BO1,204,203,200_.jpg |
| author         | The author of the book        | String(50)  | JRR Tolkien                                                                              |

Example:

```json
{
    "id": "1cc3e4de-2307-45bf-b0a6-c54f0cecb4e2",
    "title": "Children of Blood and Bone",
    "image": "https://images-na.ssl-images-amazon.com/images/I/A1agLFsWkOL.jpg",
    "author": "Tomi Adeyemi",
    "price": 11.39
}
```

**Issues**

I've tried to decouple php entities from its database definition because I prefer to use Xml or yaml entities for the entity definition, but it was not possible, I could'nt make it work, so I had
to use annotations.

A deprecation warning is launched with unit tests, without affecting its results, but it's annoying. Tried to fix it but without success.

**Improvements**

VM should be configured using Ansible, so no needs to remember or clone the original machine. Also it would be necessary for migration plans or VCS of the machine changes.

As this is a small project and only for DEV purpose, the usage of events has not been implemented, neither CQRS. In a bigger project with higher traffic,
an event could be thrown when CreateBookUseCase ends, throwing an event to a RabbitMQ queue, notifying that a new book has been entered.

CQRS could be implemented too, separating commands from queries, pre-loading a list of books after every creation book event. The GetBooksUseCase
would have a great impact on its performance.

Both unit test and integration tests are launched together, it would be recommended to separate them, in orther to execute fast unit tests
in dev machines, and integration tests inside a pipe line in Jenkins every time the code is pushed to the repository.

About integration tests, I've used an SQLite database with fake data, but a SQLite in memory database should be recommended,
loading fixtures instead of reading and writing directly to the data.db file.
 
Another approach could be using directly MariaDB for the integration tests, so it would become
a more real scenario.

Token authentication using oAuth2 should be used for production environments, so we can be sure
to establish secured stateless connections between client-server.

**Application deploy**

There is vagrant file inside the Vagrant folder, so after executing "vagrant up" command and entering into the machine,
follow these steps for a basic configuration:

- sudo yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
- sudo yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
- sudo yum-config-manager --enable remi-php72
- sudo yum install yum-utils php php-mcrypt php-cli php-gd php-curl php-mysql php-ldap php-zip php-fileinfo php-xml wget mariadb-server.x86_64 unzip
- sudo yum -y update

Enable mariadb database:

- sudo systemctl enable mariadb
- sudo systemctl start mariadb

Once inside the VM, go to the project folder "/var/www/library/current" and execute composer:

- curl -sS https://getcomposer.org/installer | php
- mv composer.phar /usr/local/bin/composer
- composer install

- wget https://get.symfony.com/cli/installer -O - | bash
- export PATH="$HOME/.symfony/bin:$PATH"

- mysql -u root
- CREATE DATABASE IF NOT EXISTS bookstore;
- GRANT ALL PRIVILEGES ON bookstore.* TO 'librarian'@'localhost' IDENTIFIED BY '123456';

**Server running**

After executing vagrant up, Symfony server is listening* to a public access, we can check it through our cli.

```
[vagrant@localhost current]$ ps -ef | grep php
root      3509  3508  0 07:28 ?        00:00:07 php /var/www/library/current/bin/console server:run 0.0.0.0:8000
root      3516  3509  0 07:28 ?        00:00:02 /usr/bin/php -dvariables_order=EGPCS -S 0.0.0.0:8000 /var/www/library/current/vendor/symfony/web-server-bundle/Resources/router.php
vagrant   3641  3589  0 09:17 pts/1    00:00:00 grep --color=auto php
```

*Symfony server could fail first time, until the application deploy steps has been completed and machine restarted.

**Unit testing**

Unit tests and integration teests executed together

```
[vagrant@localhost current]$ php bin/phpunit -c phpunit.xml
#!/usr/bin/env php
PHPUnit 6.5.8 by Sebastian Bergmann and contributors.

Testing Project Test Suite
.......                                                             7 / 7 (100%)

Time: 8.44 seconds, Memory: 36.25MB

OK (9 tests, 29 assertions)
```

**Credentials**

In order to access the VM, default vagrant user has been used.

MariaDB:
- root without pass
- library: librarian / 123456

SQLite without any restriction