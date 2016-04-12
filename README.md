# flickrer
flicker client with user rego

## setup

### build
first of all, get your usual tools ready - composer, npm, etc. 
 
- run `composer install --prefer-dist` to install php dependencies.
- run `npm install` to install frontend dependencies
- bower components are checked in, so don't worry about it.

### install db
Grab the `db.sql` file from \cfn folder and import it into your mysql db.

### apache conf
Grab the `Flickrer.cnf` file from \cfn folder and update the path to your own path, then include under apache conf dir `sites_enabled`, make sure you run `service httpd restart` or `apachectl restart` depending on which system you are on.

If you look closely, there's a `SetEnv AppEnv dev` in there, so if you want to deploy to prod, just change it to `SetEnv AppEnv prod` - just shows how to manage the envs in a super easy way.

Also make sure you add an entry to hosts so you can see it on your local:
`127.0.0.1 flicker.local`

Then you can just browse to flicker.local
 
### fun facts!
Expecting to see a lot of templates? Sorry to disappoint. I'm using my little sweet simpleJS to make this a one page app, which provides much easier logic pattern and much better code readability, for more details do checkout http://coreorm.github.io/simple.js/. So yeah, there's only a static html page, no template whatsoever.

#### structure
the whole app is done in a super simple structure:
- static html with js as the front (and css/js from public cdns so I don't need to write them)
- php backend serve as microservice - frankly, I'd use node + mongodb for this job, but you guys wanted PHP so here goes.
- As point #2 explains, php runs (with SLIM backing it) as a micro service and it only pumps out JSON data, this app is highly portable, in harsh environments, you can load balance tens of these little instances and just sit the static home somewhere in amazon. 

## test
Grab the `testdb.sql` file from \cfn folder and import it into your mysql db. 

Then simply run the following commands to test
- `npm run test` to run all tests
- `npm run test:dao` to run dao tests
- `npm run test:service` to run service tests
- `npm run test:mode` to run model tests

## compromises
The email says I get 48 hours, in reality, I get home at 7pm and I need to spend time with my 6 year old, so I get around 9pm - 12pm, 3 hours window to code this thingy. So I had to skip a few things:

- Unit test coverage is not 100% - but it's close enough
- there's no email function in user, and also i don't verify your username uniqueness - it's easy to do but i just don't have the time - this is enforced in db, however, just not done at the frontend
- I used my own JS libs for frontend so it's easier to code, but you'll need to understand the lib to understand my codes (good thing is they are straightforward).




~~~~~~~~~~~~~~~ original requirements ~~~~~~~~~~~~~~~~~~~~~~

# Flickr Image Gallery

## Question

Using an appropriate framework as a foundation, create an app which 
generates image galleries in response to user searches, drawing content 
from Flickr using their REST API.

• The app should require registration before allowing users to conduct searches.

• The search results should be paginated and displayed as five results per page, and the user should be able to navigate to other pages.

• Each image should be displayed as a thumbnail. Clicking on the thumbnail should open a new page which shows the full-size image.

• The app should maintain and display a list of recent searches made by the user. 

You are free to use whatever framework and storage technologies you deem appropriate, but you should justify your choices in the application's README.

## Notes

You should

• Submit your assignment as a Git repository hosted on either GitHub or BitBucket.

• Take the full window of time; there are no bonuses for early submission.

• Include a README explaining how to install dependencies and run their application.

• Include automatic tests and instructions for running them.

• Explain any compromises/shortcuts you made due to time considerations.
