# Coding Challenge

For convenience purposes, Docker setup is used.
SQLite used as a storage.

First, you should initialize the project by using:
```shell
make init
```
This command will build the image, install dependencies and load fixtures to DB.

Then you can run tests:
```shell
make test
```

Or, if you want to test it in a browser, you can run:

```shell
make up
```

Follow the link from command output.