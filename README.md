# sea-of-bottles

An open project to bring people together

## Presentation

Sea of Bottles main goal is to bring people together by retrieving messages and dispatch them to a random user at a random time.

## Setup

### Installation

The project require :

- `git`
- `docker` & `docker-compose` [project page](https://docs.docker.com/get-docker/)
- `task` [project page](https://github.com/go-task/task)

Clone project

```console
> git clone git@github.com:ApertureDevs/sea-of-bottles.git
> cd sea-of-bottles
```

Run installation task

```console
> task install
```

### Configuration

:warning: The default docker-compose configuration expose no port to the host.

You can edit this default docker-compose configuration by creating a custom `docker-compose.override.yaml` and configure port mapping as your need.

For example, to use 80 port for api and 8080 port for frontend you can create a `docker-compose.override.yaml` as follow :

```yaml
#docker-compose.override.yaml
version: '3.8'

services:
    frontend-webserver:
        ports:
            - 8080:4200

    api-webserver:
        ports:
            - 80:8000

    mailer:
        ports:
            - 4545:80
```

### Start dev stack

To start local environment

```console
> task serve
```

After a few seconds, `frontend-webserver` `api-webserver` `mailer` services will be available through your web browser :

- `API` through URL : local.api.seaofbottles.aperturedevs.com:*api-webserver-port*
- `Frontend` through URL : local.seaofbottles.aperturedevs.com:*frontend-webserver-port*
- `Mailer` through URL : local.mailer.seaofbottles.aperturedevs.com:*mailer-webserver-port*

> Don't forget to replace *api-webserver-port* and *frontend-webserver-port* in URL with your previously configured ports. For example, if you use the previous `docker-compose.override.yaml` configuration :
>
> - `API` through URL : local.api.seaofbottles.aperturedevs.com:80
> - `Frontend` through URL : local.seaofbottles.aperturedevs.com:8080
> - `Mailer` through URL : local.mailer.seaofbottles.aperturedevs.com:4545

## Usage

This project use `taskfile` as task runner. You can display all available task configured with `--list` command option

```console
> task --list
```
