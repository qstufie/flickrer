# flickrer
flicker client with user rego

## structure
the whole app is done in a super simple structure:
- static html with js as the front (and css/js from public cdns so I don't need to write them)
- php backend serve as microservice - frankly, I'd use node + mongodb for this job, but you guys wanted PHP so here goes.
- As point #2 explains, php runs (with slim framework backing it) as a micro service and it only pumps out JSON data, this app is highly portable, in harsh environments, you can load balance tens of these little instances and just sit the static home somewhere in amazon. 
- db: mysql

Reason for mysql: I'd pick mongodb + nodeJS for this any time, as it being atomic and self contained - there's no cross-user joins or anything, everything is around the one user that's logged in, however, this is a php question, and mysql is just handy enough so it will do the job - strictly speaking, there is indeed a little join we can do with recent_searches, however the requirements don't really ask for a 'grab a few users and all their recent searches' so it's not really being used.

### framework used:
- backend: php slim framework
- frontend: simple.js (my little baby, hasn't got time to promote it yet)

### fun facts!
Expecting to see a lot of templates? Sorry to disappoint. I'm using my little sweet simpleJS to make this a one page app, which provides much easier logic pattern and much better code readability, for more details do checkout http://coreorm.github.io/simple.js/. So yeah, there's only a static html page, no template whatsoever.

## Setup - Vagrant Setup (recommended - super easy)
I won't waste time talking about getting vagrant and virtualbox, I suppose that's all done, now.

In project root

- run `composer install --prefer-dist` to install php dependencies.
- then run `vagrant up`
- Then once it's done, run `vagrant ssh`
- Then run all the following commands (just copy/paste and hit enter).

```
cd /var/www/cfn
sudo cp flickerer.vagrant.conf /etc/apache2/sites-enabled/
mysql -u root -proot < db.sql
mysql -u root -proot < testdb.sql
sudo apachectl restart
```

Finally, add the following to your hosts file (on your own host machine, not vagrant machine!):
```192.168.33.10	flicker.vagrant.localhost```

Then browse into http://flicker.vagrant.localhost/index.html, and that's it!

*if you don't want to setup this in a tranditional way, you can just stop reading here*


## Setup - manual

### build
first of all, get your usual tools ready - composer, npm, etc. 
 
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

## Test
Grab the `testdb.sql` file from \cfn folder and import it into your mysql db. 

Then simply run the following commands to test
- `npm run test` to run all tests
- `npm run test:dao` to run dao tests
- `npm run test:service` to run service tests
- `npm run test:mode` to run model tests

## compromises
The email says I get 48 hours, in reality, I get home at 7pm and I need to spend time with my 6 year old, so I get around 9pm - 12pm, 3 hours window to code this thingy. So I had to skip a few things:

- <strike>Unit test coverage is not 100%</strike> Yes it's 100% covered over core layers - DAO/Model/Service, only the main App router is not included due to time constraints
- there's no email function in user, and also i don't verify your username uniqueness - it's easy to do but i just don't have the time - this is enforced in db, however, just not done at the frontend
- I was planning to do continious build/integration on JS resources but just don't have time, so I have all the dependencies there just don't really have the process there yet... anyway, so here comes the big app.js and separate js for other pages
- <strike>also don't have time for fancy loadings guys... I only got 6 hrs in total</strike> I lied, actually I just got a bit of extra time today and I added an animated loader!!

## extra stuff I did
- freaking awesome ORM layer on top of mysql
- full-fledged micro-service structure that is made for web but can be extended to work for native app too.
- nice npm commands in package.json for easier CLI fun
- (old) Google style pagination
- fully responsive single page web app
- did I mention vagrant? This whole thing is self-contained and you don't need a local env.


## ~~~~~~~~~~~~~~~ original requirements ~~~~~~~~~~~~~~~~~~~~~~

### Flickr Image Gallery

#### Question

Using an appropriate framework as a foundation, create an app which 
generates image galleries in response to user searches, drawing content 
from Flickr using their REST API.

• The app should require registration before allowing users to conduct searches.

• The search results should be paginated and displayed as five results per page, and the user should be able to navigate to other pages.

• Each image should be displayed as a thumbnail. Clicking on the thumbnail should open a new page which shows the full-size image.

• The app should maintain and display a list of recent searches made by the user. 

You are free to use whatever framework and storage technologies you deem appropriate, but you should justify your choices in the application's README.

#### Notes

You should

• Submit your assignment as a Git repository hosted on either GitHub or BitBucket.

• Take the full window of time; there are no bonuses for early submission.

• Include a README explaining how to install dependencies and run their application.

• Include automatic tests and instructions for running them.

• Explain any compromises/shortcuts you made due to time considerations.
