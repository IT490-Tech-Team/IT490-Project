# Frontend

* **`Website/`**
  * *`index.html`* ➜ Homepage
  * **`common/`** ➜ Non-website hosting folder for all common files shared by websites.
    * **`utils/`** ➜ folder containing utility files, such as files for creating RabbitMQ client requests.
    * **`javascript/`** ➜ folder containing shared javascript files such as  *`navbar.js`*
      * *`authenticate.js`* ➜ function that handles all authentication (including validating the session)
      * *`defaults.js`* ➜ js file for default variables such as UTILS_PATH
      * *`helpers.js`* ➜ js file containing common but small functions such as helper functions such as getCookies, setCookies, and validateSession 
      * *`navbar.js`* ➜ js file which runs at the beginning of a website loading to insert the navigation bar. This allows for universal navbars
    * **`styles/`** ➜ folder containing shared styling files such as
  * **`login/`** ➜ Folder containing everything for the login page.
  * **`register/`** ➜ Folder containing everything for the registration page.
* *`bookquest.conf`*
  * apache2 config file
* *`copy_website.sh`*
  * copies the contents inside of website and puts them inside of /var/www/html
* *`monitor_website.sh`*
  * monitors the **`/website/`** folder for changes. It then runs *`copy_website.sh`* every time a change is made to the folder.


## Guide

1. run *`setup.sh`*
2. run *`copy_website.sh`*

## Making changes to the website

1. run *`monitor_website.sh`*


## How to create a new page for the site.

1. Go to the **`website`** folder
2. Create a new folder such as "login"
3. Create an index.html

This will make a new path for example, if you follow the instructions for "login" then you will be able to access that webpage from "http://localhost/login"