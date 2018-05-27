# Camagru
## Presentation
Website to take, edit and share pictures in PHP and JS Vanilla.
You can test it live here : [Test my Camagru](https://camagru.thomasbs.fr)

### Main features
* User register/login
* Picture capture with filters (transparent images/stickers)
* Comments and Likes
* Profile page
* Infinite scroll gallery
* Dynamic navigation thanks to AJAX (still with JS vanilla)

### Screenshots
![Feed page](https://github.com/tbailly/Camagru/blob/master/screenshots/feed.png)
![Comments page](https://github.com/tbailly/Camagru/blob/master/screenshots/comments.png)
![Take picture page](https://github.com/tbailly/Camagru/blob/master/screenshots/take-picture.png)
![My pictures page](https://github.com/tbailly/Camagru/blob/master/screenshots/my-pictures.png)

## Getting started
### Prerequisites
To install this website you'll need:
* A running server with FTP access
* A MySQL host, with access to it, and ability to create a new database

### Installing
To install this website on your server, first download the repo, and keep the app folder. You have to make some changes in the code with your favourite text editor.

```
In config/database.php:
Change your database credentials. Be carefull with your DB name because if you already have a DB with this name, it will be completely erased.
```
```
In config/setup.php:
If you want to clean your database before installing camagru, uncomment the lines 15/16.
```
__Be careful, your web root should be the `public` folder. Setup your server accordingly__

## Deployment
After everything is setup, transfer all the files from `app` folder to your server. Then connect toyour website from a browser and go to `http://yourwebsite.com/config/setup.php`. And it's done !

## Built with
* HTML5 / CSS3
* Bootstrap (v4)
* Javascript (vanilla)
* PHP 5.6

## Authors
* **[Thomas Bailly-Salins](https://github.com/tbailly)** - *All*
