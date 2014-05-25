Slim Image Archive
==================
__Simple__<sup>*</sup>__, fast and secure archive for images:__

* Create multiple categories with multiple albums with multiple images!
* Manage multiple users with different privileges!
* Easily navigate between all those things!
* Protect your photos from the NSA! <sup>(_not really_)</sup>

The purpose of this project was for internal use of a company, so no big effort went into the presentation - it's simple and clean bootstrap ready for further improvements.

<sup>(*) _I won't argue if you'll say that this code is an overkill._</sup>

![Slim Image Archive](https://dl.dropboxusercontent.com/s/0yyixpexii1v8ir/slim-image-archive-2.png)
![Slim Image Archive](https://dl.dropboxusercontent.com/s/7txe08yay1seftp/slim-image-archive-1.png)

##### Dependencies
* PHP 5.4 (short array syntax)
* [__Slim Framework:__](https://github.com/codeguy/Slim) 2.*
* [__Twig templates:__](https://github.com/fabpot/Twig) 1.*
* [__password_compat:__](https://github.com/ircmaxell/password_compat) dev-master
* [__jQuery File Upload:__](https://github.com/blueimp/jQuery-File-Upload) 9.5.7
* [__DataTables:__](https://github.com/DataTables/DataTables) 1.10.0
* [__Twitter Bootstrap:__](https://github.com/twbs/bootstrap) 3.1.1
* [__Magnific Popup:__](https://github.com/dimsemenov/Magnific-Popup) 0.9.9

and a slug method taken from:

* [__Fat-Free Framework:__](https://github.com/bcosca/fatfree) 3.2.2

##### Installation
* `git clone https://github.com/ksdev-pl/Slim-Image-Archive.git`
* `composer install`
* Import slim-image-archive.sql to your database
* Configure your database connection in `/app/app.php` lines 60-63
* Point web root to `/public` folder, the rest should be protected
* Add write privileges to `/tmp` folder (with subfolders)
* Sign in with email: `admin@admin.com` and password: `admin`
* Create new user with admin privileges and delete default account

##### Things to know
* If you wish to change allowed types of files that user can upload, update `$mimeTypes` in `Image` model, and both `.htaccess` files under `/files` folder and its `/thumbs` subfolder
* In `/app/views/image/create.html.twig` you can change `maxFileSize` option for upload script
* Exception logs are stored in `/tmp/logs`
* User roles and corresponding privileges:
  * Normal - upload only
  * Extended - upload, edit & delete
  * Admin - manage users

##### License

Slim Image Archive is released under the [MIT license](http://opensource.org/licenses/MIT).