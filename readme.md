
## Code Structure and Essential information reference:

Resource: https://docs.google.com/document/d/1K33oV26y1N963cxYPK7f6nvJlM4i_fM-l6qde3LZy7E/edit


## SeoAgent Site Installation

#### Project Install

1. `git clone git@bitbucket.org:inkgroup12/new_seo_third_party.git soagent`
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



## Admin Site Installation

1. Set up `NewSeoTagManager` module first
2. Dump mysql data (You can check related tables through `/Models` directory within the module
3. Ensure your model connection Base class is correct for `/Models/InkStationDatabase`, `/Models/LaravelDatabase` and `/Models/LogThirdPartyDatabase`

## Some common questions:
1. The module can only be accessed by a few staff, the logic is defined under SeoManagerController -> checkAuthUser
2. `token.txt` has the API key that is used to connect with Sea Agent site, NEVER COMMIT THIS FILE
3. If you encounter any auth issue, you can try to comment out middleware in routes.php


## Connecting Admin with Agent site
1. Open your agent site host and got to route `/app/api-management`
2. Create `Personal Access Tokens`
3. Copy the pop-up token
4. Go to your admin panel source code and find token.txt and paste the token you just generated (never commit this file!), double check it does not include new extra new line
5. Go to your admin panel source code and find SeoAgentApiService under Services directory, change the serve api to your local Sea Agent site url
6. Now, go to your admin panel host and go to SEO tab and click SEO Config, you should be able to see the last sync time settings which retrieved from your local SEO Agent site by API calls.
7. Now, click Push data to agent button to push the sea meta data to your local SEO Agent site, wait the spinner to be finished. Once its done, go to your SEO Agent site host in `/app/seoagent`, you should see a list of synced meta data.
8. Now, click the Push button in Google Search Keyword section in admin panel. (This takes a while to finish sync), Once its finished, go to your SEO Agent Site -> View Keywords page to query data for 2019-03-25



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