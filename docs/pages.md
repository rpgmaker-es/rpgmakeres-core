## Understanding the page system

RPG Maker ES Core woks in a different way than most PHP based frameworks around there. For improving speed and loading times, most of the sections of the website are **automatically pre-generated**. That means when a user access, for example, to a game information page, the web server will just load a pre-rendered HTML page instead of a PHP script.  Also, as most content based websites, the content will need to be updated (for example, for updating game information, a new comment, corrections or page deletions). In that case, RPG Maker ES Core will update that HTML automatically based on triggers (for example, when a user uploads a new image) and/or by a period of time (for example, 3 times a day for rotating featured projects). 

However, not all pages will be served by that basis. There's pages that always need to run dynamically (for example, user login, upload validation, administration panels, and so on). In these cases, RPG Maker ES Core makes an exception and it offers these pages to load dynamically, like almost any MVC based PHP framework. That means, each time a user wants to access to a dynamic section of the webpage, it will always be evaluated by a PHP script (instead the other ones that are already rendered in HTML pages). 

So, keeping things short, there's 2 categories of pages on RPG Maker ES Core:

- Static pages (pre-rendered in HTML and updated ocasionally).
- Dynamic pages (always to be evaluated in PHP).

## Static/dynamic controllers and MVC silly stuff.

Hey, as you already figured, this is a MVC (somehow) based PHP framework. So, you might expect controllers be involved in all of this. And yeah, you're right. If you read the past paragraph you might expect you will have Static and Dynamic controllers here, each one serving it's own purpose in a different way. BUT HOLD ON A SECOND.  This doesn't work exactly as most MVC frameworks out there for static ones; instead It will work like this:

- Some trigger (time/website event) will trigger an controller. 
- The controller do silly stuff and makes a fancy HTML output.
- The output gets written to the www public folder, ready to be served by a web server.

In the other hand, dynamic pages will work more similar than these MVC frameworks:

- A user navigates to a specific route, triggering the controller.
- The controller do silly stuff and makes a fancy HTML output.
- The output is shown in your screen (or curl/wget/webcrawler, who knows XD). 

See the difference?

## Okay, I got it, but how I make pages then for that thing?

Okay, let's go to into the interesting part.

### Creating a controller.

The first thing you need to know is what the hell your page will do. Think a little what function will have, what will be the route, and so on. Got it? Fine, then go to the /controllers folder and make a copy any of these test controllers there. These are useful as a template :D.

As you can see in the controller file, a Controller is basically a PHP class with some methods in there. There are mandatory ones that you need to put some attention to it.

- **_getWebPath()**: It returns the route that you controller will have. A route is basically https://an.end/point/that/your/page/will/be/accessed/from/web .  Without this, your page will be innaccesible for the web. 

- **_getDefault():** When a user access to this page without any arguments at all (example: rpgmaker.es/games/), what page of the controller you should load? Then, this function returns what page the controller must load and with which parameters. The format is the same that **_getChildren()** (see below).  

- **_getChildren()**: When you are making a page, for sure that one will have sub-sections, isn't? If it's a comments section, you will have pages (1,2,3,4), or if it's a game page, then you will have games on it (Maldicion del Limo 2, El gofre misterioso, etc). This function returns an array of sub-elements (sub-pages, children in other ways), that your controller will have. Each element consist on the following:

  | Index | Description                                                  | Example                                                      |
  | ----- | ------------------------------------------------------------ | ------------------------------------------------------------ |
  | 0     | Child name. The name of the route that the page will have (please respect web names). | If you controller have the route ``/games/``, and your children is called ``the-heroe-2``, then the complete route will be name ``/games/the-heroe-2`` |
  | 1     | Callback. The method inside this controller that will be called when a user access to that children. | With the past example, if you have ``the-heroe-2`` as a child name, then a callback can be ``gameInformation``, meaning that ``Controller->gameInformation()`` will be called when the user enters to ``the-heroe-2`` |
  | 2     | Any parameter you can pass to the past callback. If you have nothing to pass by, a ``null`` is very welcomed :) | With the past example, with ``Controller->gameInformation()`` be called for ``the-heroe-2`` , a parameter can be 1083 (game ID for example). So when the user wants to see ``the-heroe 2``, ``Controller->gameInformation(1083)`` will be called. |

  In the examples this list is fixed. However if you have a dynamic list of items (a list of games for example) you are welcomed to build this array dynamically (as a idea, maybe some sort of MySQL call and then fill children with each game url). 

And that's it about the mandatory methods of a controller. Now you can create any methods as you want (don't forget to implement the callback for your beloved children ^^). Inside there you must put the server logic/magic and generate the HTML output; you can take a peek at [generating_html](generating_html.md).  



### Registering it as Static or Dynamic.

The next step now, is decide if the new controller will be a dynamic or static page. That information is stored into **routes.php**  file. Do not scare, there are already filled in with real site parts and examples. Inside you will see in that file 4 key-value arrays:

- **controllers:** Add your controller name here and the name of your new controller file here. 
- **staticPages:** If your controller will be a static page, then add the controller name here, with a number. What's that number? It's the amount of minutes that RPG Maker ES Core will wait to re-generate the page. If you do not want that your controller will be generated in a period of time (just by demand, for example, when a user do X action), then just put 0. 
- **dynamicPages**: If your controller will be a dynamic page, then add the controller here. Right, you're done. 
- **blackListPages**: If you are tired of a page/controller and you want to delete it, delete it from the static/dynamic page list and add it here. So RPG Maker ES Core will safely clean it in the next update. 

**WARNING:** It's **SUPA-IMPORTANT** that you delete the page from dynamic/static list before putting on the blacklist. Otherwise RPG Maker ES Core will be in a endless loop of generating-and-deleting page in each-update. 

### Making it work.

It's time to make your new page shine :D . In this step RPG Maker ES Core will generate the page (or the PHP endpoint if you made a Dynamic page) to the public www folder, ready to be served. For that purpose, then you must go to the CLI and type:

``php cron.php -u`` 

More information of CLI on [cli](cli.md). 

**NOTE:** If you have a cron already configured, maybe you will want to wait the amount of time you configured instead of executing this command. However, I personally don't recommend this way, since bad things can occur if you made mistakes when building the controller; and if it happens, you will be the first person to know, as the error will be printed directly into your CLI instead in a nasty cron log file somewhere (at least you already have a good log system that you love).  

### Updating static pages on demand

Do you remember when I said in **routes.php** part that you can update static pages on demand instead by a period of time? Yeah, you can do it in any part of the code (please be neat and put in on a controller method as a civilized person you are :D ) , by just calling: 

``WebGenerator::generateSingle(controllerName, true);``

Where ``controllerName`` is the name of the controller as you defined it on **routes.php** file, and that ``true`` ... leave at it is (It means it will be be updated even if the page already exists on the public www folder).

If you want to update **ONLY one child** of your page, it's not necessary that you need to rebuild the whole controller for this. Instead, use this:

``WebGenerator::generateSingle(controllerName, true, route);``

The same as above, but with an extra parameter: The child route name. 

**NOTE:** If you want to update the default one, use ``//__default__// `` as route name. 

Of course, you can update it too from CLI.

``php cron.php --write=controllerName --force`` 

or

``php cron.php --write=controllerName --force --child=childRoute`` 

Same parameters as above. 