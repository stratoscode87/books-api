# Your book review service

Develop the following service using a language of your choice between [Go, Python, PHP].
You're free to use any framework and library, but be prepared to have to explain your choices!

Make the service easy to run either using a script, a Dockerfile or writing the commands in a documentation file.
And make sure to write some tests!

Use git for version control and publish this exercise as a public repository on a platform like github, but please do
not leave references to the company

## Instructions

We have included a simple compose file to make it easy to run some support services that you could need, but feel free
to change or use something else completely.

To use it you need to have Docker installed, then you can run:

```bash
docker compose up
```

In this project you must use and integrate some calls from the OpenLibrary API.

You can find more details here: [openapi.org](https://openlibrary.org/developers/api)

## Installation

To use the development environment you need to have [Docker](https://www.docker.com/) installed.

1. **Clone the repository**

```bash
git clone https://github.com/stratoscode87/books-api.git
```

2. **Run setup.sh**

```bash
bash setup.sh
```

(or run all the commands contained in the setup.sh file)

## Endpoints

The repository contains the Postman collection for these APIs. Import the file **Books_API.postman_collection.json**
into
Postman if you think it might be useful to you.

After installation the APIs will be reachable at http://localhost/api

To use the API it will be necessary to register a user with the Register API and request the Bearer with the Login API.

All requests must have the header **Accept: application/json**

```http request
POST /register
Accept: application/json
```

```json
{
  "name": "John Doe",
  "email": "test@test.com",
  "password": "123pwd"
}
```

- Register a new user

```http request
POST /login
Accept: application/json
```

```json
{
  "email": "test@test.com",
  "password": "123pwd"
}
```

- Fetch the Bearer Authentication Token

```http request
POST /logout
Accept: application/json
Authorization: Bearer {{token}}
```

- Expire the token in use

```http request
GET /search?{keywords}
Accept: application/json
Authorization: Bearer {{token}}
```

- Search on openlibrary, give back the work_id (and whatever seems appropriate) of matches

```http request
POST /review
Accept: application/json
Authorization: Bearer {{token}}
```

```json
{
  "work_id": "",
  "review": "text",
  "score": 6
}
```

- Validation (check the work_id matches on openlibrary, score range, review characters)
- Save the request to give back a reference for async processing
- Save on DB the complete data (asynchronously, enrich the data via openlibrary with cover image, metadata, etc.)

```http request
GET /review/{id}
Accept: application/json
Authorization: Bearer {{token}}
```

- will reply with 202 while it's processing
- will reply with 200 and your enriched data when everything is ready

```http request
PUT /review/{id}
Accept: application/json
Authorization: Bearer {{token}}
```

```http request
DELETE /review/{id}
Accept: application/json
Authorization: Bearer {{token}}
```

## Tests

To run the tests executing the command

```bash
docker compose exec app php artisan test
```

## Misc

It will be appreciated if you want to implement a login system.

feel free to add everything you think is useful and necessary for writing good quality code: automatic tests, static
code analysis, automation of coding standard etc.
