# Bad Website

This project includes some of the worst code I can think of.

The question is, can you find all the problems?

There are 3 main areas to focus on:

1. [Security](./public/security/) ([answers/cheats](./public/security/answers/))
2. Accessibility (TODO)
3. Performance (TODO)

The order is not important, as a developer you really need to think about all 3.

---

## Setup

Edit the `config.php` file, so it includes the connection details to a single MySQL database.

Really, the connection details should only have access to 1 database, one that will be exploited.

Run the following command, to populate the database, and reset some file/folder permissions.

	./reset/reset.sh

Then set the DocumentRoot for your web server (Apache/nginx/etc) to the `/public/` folder.

And please don't run this website so that anyone else can view it (even on your local network)... otherwise your own computer might get hacked.
