## RPGMaker.es core

###Setup
You will need:
- A web server
- PHP 7.x connected to your webserver and in CLI

#####And eventually ... (optional)
- A database, SQL compatible, capable of connecting with PHP via PDO interface.

###Setup
- Put all files in your PHP-enabled server, and point your web server to public folder.
- Make a copy of config-example.php , rename it config.php and tweak it as you want.
- Run php cron.php -u for generate all webpages. 
- (Optional) Setup a cron that runs php cron.php -u in a certain amount of time to keep pages updated.

###And... something more?
Well.. there will be someday more time for fancy readme files. :D
