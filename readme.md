# Bad Website

This project includes some of the worst code I can think of.

The question is, can you find all the problems?

There are 3 main areas to focus on:

1. [Security](./public/security/)
2. [Accessibility](./public/accessibility/)
3. [Performance](./public/performance/)

The order is not important, as a developer you really need to think about all 3.

---

## Setup

Edit the `config.php` file, so it includes the connection details to a MySQL database.

The login to this database should only have access to 1 database.

Run the following command, to populate the database, and reset some file/folder permissions.

	./reset/reset.sh
