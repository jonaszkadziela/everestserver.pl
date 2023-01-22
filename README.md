# Website everestserver.pl
Beautiful & responsive website for EverestServer.

## A few words of introduction:
This is a website I created for my home server. Currently, it serves as a hub, so that the users are able to select which service of the server they want to use.

## Used technologies:
* HTML
* CSS
* Sass
* Bootstrap
* JavaScript
* Webpack
* Handlebars

## Prerequisites:
* Install npm (it comes bundled with [Node.js](https://nodejs.org/en/download/))

## How to set up locally?
1. Clone the repository
	```
	$ git clone https://github.com/jonaszkadziela/everestserver.pl.git
	```
1. Enter **everestserver.pl** project directory
	```
	$ cd everestserver.pl
	```
1. Install all dependencies
	```
	$ npm install
	```
1. Run development web server
	```
	$ npm run serve
	```
1. Project should be running at [localhost:8080](http://localhost:8080)

## Development:
* All source files are located in the `src` directory. When building the project, everything will be bundled using Webpack and outputted into the `dist` directory
* The following custom `npm` commands are available in this project:
	* `clear`: Clears the build. This removes all files from the `dist` directory, except for `.gitignore`
    * `dev`: Alias for the `development` command
    * `development`: Builds development project using Webpack. Adds additional files for easier development and debugging
    * `prod`: Alias for the `production` command
    * `production`: Builds production project using Webpack. Makes optimizations for production
    * `serve`: Starts a development server that serves the project at [localhost:8080](http://localhost:8080)

## Links:
* Live website: https://everestserver.pl
