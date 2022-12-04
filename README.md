## Holaluz Trial ##

This is my attempt to the Holaluz trial. It is written in PHP 8.1 and uses Symfony framework version 6.1.

**Architecture:**

Application code is organized as follows, based on [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html) from Robert Martin:

- Reading/Application: Contains the adapters that convert the Domain entities to  more convenient DTOs for the upper layers.
- Reading/Domain: This is where the bussiness logic is hold. In this example contains the *Reading* entity and the interfaces which implementation is delegated to the Infrastructure layer.
- Reading/Infrastructure: Concrete implementations of Domain interfaces that are coupled to third party code.
- Reading/UseCase: Where the use-case implementations reside. They are implemented using CQRS pattern.
- Shared/Infrastructure: Needed libraries that support the domain-especific infrastructure code.
- Shared/Io: Implementations of external, Input/Output services. In this case, the implementation for the CLI based on the Symfony/Console component.

**Usage:**

First it' i's necessary to run composer to install all the required third party libraries and the class autoloader:

```bash
docker-compose run --rm composer
```
Then, we can run the application:
```bash
docker-compose run --rm php bin/console holaluz:trial:run filename.ext
```
Please note that the two files provided with the execise documentation are *\*not\** included with the project files. You are expected to place them inside `var/storage/local` folder before proceeding.


**Testing:**

The project includes a small suite of automated tests.
Use the following command to run the test suite:
```bash
docker-compose run --rm php vendor/bin/phpunit -c ./phpunit.xml --bootstrap ./tests/bootstrap.php
```

