

## SeoAgent Site Installation

#### Project Install

1. `git clone`
2. Create a schema in your database e.g. seoagent and Connect your mysql to .env file
3. Run `composer install -vvv`
4. Run `npm install`
5. Run `php artisan key:generate`
6. Run `php artisan migrate:refresh`
7. Run `php artisan passport:install`
8. Run `php artisan passport:keys` (- install applications OAuth2 secrets (if it shows the key already exist, then skip it)

#### Now lets set up a queue in your local:
1. Go to your .env file:

````
QUEUE_CONNECTION=database
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
````
(Register a PUSHER account via https://pusher.com/)

2. Open a new terminal and go to your project and run: 

`php artisan up`

`php artisan queue:work database --queue=high,default`

(If you make any changes to job or file, you have to run queue:restart to re-compile the files and re-run the command above, if you want it to be real time, please use queue:listen instead)


#### Playground

1. Go to your host
2. Go to `/register`
3. Register an account on the page with Secret code: `inkstation` (admin can see the api management tab)

#### Real time compiler

Any time you make changes to any file of project, the front-end UI will be auto-refreshed.

1. Go to webpack.mix.js and change:
`mix.browserSync('http://seoagent.test/');`  to your own host

2. Run `npm run watch` (a browser tab should be opened automatically)


## Queue on production server

1. Install supervisor, Read through: https://laravel.com/docs/5.7/queues#supervisor-configuration

````
command=php artisan queue:work database --queue=high,default --sleep=3 --tries=3
````
note: change your artisan location

2. start supervisor


## Scheduler on production server

1. read through cron set-up: https://laravel.com/docs/5.7/scheduling#introduction
2. The current scheduler is defined in `app/Console/Kernel.php`, `ReRunCurrentDateSummaryCommand` this is used to be run on every end of day.
