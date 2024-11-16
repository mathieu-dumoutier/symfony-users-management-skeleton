# Symfony Users Management skeleton

A skeleton repository for start new project with users management features inside!

![CI](https://github.com/mathieu-dumoutier/symfony-users-management-skeleton/workflows/CI/badge.svg)

Created with Symfony 7.2, current version : 7.2

## Start & stop

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

* All features of [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker)
* Entities for users management : User, Role, Permission

**Enjoy!**

## License

Symfony Users Management skeleton is available under the MIT License.

## Credits

Symfony-docker is created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
