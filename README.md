Link Shortener Example
======================

This is a simple demo project.

It supports:
* Creating, viewing, and deleting short links
* Allows for custom vanity URLs
* Allows users to see how many times their link has been visited
* Uses a basic REST API
* Supports frontend and backend validation
* Frontend is written in React

I tried to keep things fairly simple. I'm sure there are libraries I could have leveraged to simplify some of the functionality, but this project is simple enough that it didn't feel warranted. There is also plenty of room for refactoring, but that would only be necessary if additional features or complexity were added.

### Installing

1. Ensure that the Symfony command line is installed and is globally accessible `curl -sS https://get.symfony.com/cli/installer | bash`
1. Clone the project `git clone https://github.com/trappar/link-shortener-example`
2. Install composer vendors `composer install`
3. Install npm vendors `yarn` OR `npm i`
4. Modify `DATABASE_URL` in .env as necessary to point to a valid MySQL instance
5. Run `php bin/console doctrine:database:create` 
6. Run `php bin/console doctrine:schema:create` 

### Running

Simply run `yarn start` or `npm run start`

The project will be accessible at http://localhost:8000