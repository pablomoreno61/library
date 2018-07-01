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
- Application: It contains all the inputs to the API RESTful, including controllers, use cases, frontend exceptions and json objects
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

I've tried to decouple php entities from its database definition but it was not possible, I could'nt make it work, so I had
to use annotations. Xml or yaml entities should be used for the entity definition.

A deprecation warning is launched with unit tests, without affecting its results, but it's annoying. Tried to fix it but without success.

**Improvements**

As this is a small project and only for DEV purpose, the usage of events has not been implemented, neither CQRS. In a bigger project,
an event could be thrown when CreateBookUseCase ends, throwing an event to a RabbitMQ queue, notifying that a new book has been entered.

CQRS could be implemented too, separating commands from queries, pre-loading a list of books after every creation book event.

Both unit test and integration tests are launched together, it would be recommended to separate them, in orther to execute fast unit tests
in dev machines, and integration tests inside a pipe line in Jenkins every time the code is pushed to the repository.

About integration tests, I've used an SQLite database with fake data, but MariaDB should be used in an integration test, so it would become
a more real scenario.

Token authentication using oAuth2 should be used for production environments, so we can be sure
to stablish secured stateless connections between client-server.

**Credentials**

In order to access the VM, default vagrant user has been used.

MariaDB:
- root without pass
- library: librarian / 123456

SQLite without any restriction