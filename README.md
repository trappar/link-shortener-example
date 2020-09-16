Link Shortener Example
======================

This is a simple demo project.

It supports:
* Creating, viewing, and deleting short links
* Allows for creation of custom vanity URLs
* Allows users to see how many times their link has been visited
* Uses a basic REST API
* Supports frontend and backend validation
* Frontend is written in React

### Motivation

I try to strike a balance between writing solid extensible code and not over-enginering solutions. In this case I'm sure there are libraries I could have leveraged to simplify some functionality, but this project is simple enough that it didn't feel warranted. I think that my solutions leave sufficient room for adapting to changes in requirements - and that's what's really important. 

Some examples in my decision making process were:

* I could have pulled several methods out of the LinkController and moved them into a service. This would have made it easier/possible to unit test and improved separation of concerns, but also would have cost time, fragmented the code, and would not have reduced code duplication. I opted to keep things simple.
* I could have used the Symfony form system for at least handling the form processing, but it just felt like overkill. The boilerplate would have outweighed the code it replaced.

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