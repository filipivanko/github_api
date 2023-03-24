# ScoreApi

## Project installation
### Prerequisites
if not installed; install PHP, Symfony CLI and Composer for your OS

The project used PHP 8.1, Symfony CLI 5.5.1 and Composer 2.5.4

if on Windows; enable SQLite driver

### Install
Pull project form GitHub

To install dependencies in the project directory run
```
composer install
```

To start server run
```
symfony server:start 
```

## local endpoint
http://127.0.0.1:8000/score/{search_term}

## response
for route 127.0.0.1:8000/score/php

```
{
    "data": {
        "term": "php",
        "score": 3.44
    }
}
```
term represents the word that is searched for in the remote repository
the score ranges from 0 to 10 and represents the ratio between the number of occurrences of the term followed by the word rocks and the occurrences of the term followed by the word sucks.

## database
SQLite database is included in the project(this is just for easier testing, I know its bad practice :) )

## authentication
X-AUTH-TOKEN authentication header

### authentication tokens 
valid authentication tokens included in the database:

```
7c20183bd441e6517269d9603b8c22aa270ccf7b
b65593b732d074e5906c63e1501420c7b3963780
```

## Notes for further development

1. To switch to a new external data provider implement a new class that uses the CounApi inerface
2. Pass the object of the new class to the ScoreCalculator to get the score calculated from the new datasource
