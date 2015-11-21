FreeGeo API
===

The FreeGeo API is an open source geolocation API intended to help developers of smaller applications utilizing geocoding, searches, boundaries and other geo related tasks. The API si absolutely free to use, and completely open source. 

The project by @YupItsZac is currently live in a test phase and is available at [freegeo.yupitszac.com](http://freegeo.yupitszac.com). All docs, etc can be found there.

**Current Version:** 0.01

**Release Date:** November 1, 2015


Things To Do
===

- [Priority: High] Change DB from MySQL to something more suitable like MongoDB or PostgreSQL. Needs discussion. 
- [Priority: Low] Develop some sort of caching mechanism to improve performance.
- [Priority: Medium] Add usage examples for more development languages (Java, JS, etc).
- [Priority: High] Remove raw SQL and use Doctrine (or some other ORM) builder functionality.
- [Priority: Low] Figure out a better way to provide DB data other than .sql dumps.
- [Priority: Medium] Move all documentation to some type of storage, to get rid of each template file. This would include developing some logic to deliver that information when requested. 
- [Priority: High] Move logic for generating emails to separate controller. Also separate Mailgun creds.


Contribute
===

Like most projects, the FreeGeo API is never finished. We are constantly making changes and updates to enhance the functionality, performance and security of the API. It is strongly encouraged that interested developers from anywhere in the world contribute to the project. 

######Areas of Interest

There are three areas of primary interest on this project. Each area presents it's own unique challenges, and opportunities fr contribution. Experience with the Symfony2 framework is very useful, but not required. The entire API wa sbuilt as a tool for me (@YupItsZac) to learn the framework. 

**Backend** 

The API is built on the [Symfony2 framework](https://symfony.com/doc/current/index.html). This means that the bulk f the project is written in PHP. Interested in contributing on the backend? Great! We need your help. Experience with PHP, MVC structures, routing, etc would be ideal.

**Frontend**

For the FreeGEO API, we refer to the frontend as anything rendered in the browser. This means pages on the main website, emails the API delivers, etc. This area will utilize CSS, JS, and other frontend tools that might be added later on. 

**Documentation**

This is a HUGE area for the project. We need clear usage documentation so developers know how to use the API and can see which endpoints are available. This documentation would be rather technical, so it might be useful if you have a background in technical writing but that is not required. 

**Usage Examples**

Currently, usage examples are provided for each endpoint in PHP and Python. We would like to expand this to include more languages to make API access possible for most projects. 


**Other Stuff**

Don't want to help out in one of the areas above? No worries! The beauty of open source projects is that you can work on whatever your good at (or want to be good at). Think another area should be added? Let me know!


######How to Contribute

Fork the repo! That way you can play around with whatever changes you want to make without affecting this repo. After your changes have been made, tested and reviewed, submit a pull request to the staging branch. 

Your new code will be reviwed again, tested again, then if it makes sense to move it to Master, it will be added to prod in the next release. 

Local Dev
===

You'll of course need to run a local dev to develop for the FreeGeo API. Just use whatever tools you're used to. Below is a basic guideline to ensure FreeGeo will run on your local machine. 

**Vagrant** 

We use [Vagrant](http://www.vagrantup.com). It makes it easy to build and deploy local development environments through the use of VMs. You'll need to meet the requirements for Vagrant. 

An example Vagrantfile can be found in [this gist](https://gist.github.com/YupItsZac/ac6967d90eaed9966941)

**Database**

Currently, FreeGo uses a MySQL DB to store the geospatial data. You can find the DB info we use on dev (for local only) in the project config file. DB changes are handled via [migrations](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html).

The DB migrations are just structure of the DB for now. (I know, it's a crappy way to do it. Wanna fix it? :)) I've included SQL dumps with the data and a demo app you can use for development. I would like to (and we really need to) change from MySQL to a more appropriate DB, so I don't want to spend a lot of time on this yet. Migrations will be greatly improved when a DB is chosen and we transition to that.

**Dependency**

We use [composer](http://www.getcomposer.org) to include all of the required plugins, etc. This is a requirement. If you want to make a change that required an external plugin, it MUST be installed using composer. You'll need to run the composer install command at the root of the project once it's cloned from your fork.

```
  composer install
```

This will install all of the required packages we use. 






