Account System
============

A basic account system written in object-oriented style for PHP + MySQL. 

Why use this? 

It's:

* Object oriented.
* Uses PDO for database comparability + prepared statements for security.
* Provides the basic functionality you need for an account system, but it's not bloated.
* Does password hashing right - using password_hash() and BCRYPT.
* Has two different methods of handling logins - sessions and cookies.

I wrote this system for another project I was working on, so it's licensed under UNLICENSE. 
This means that you can use this and incorporate it in any application you want without needing
to credit me or worry about infringement.

Should you make any improvements or notice something that should be fixed here, I would love to
hear about it!

Requirements
============

This library requires `PHP >= 5.5.0`, because it uses the password_hash function. 

If you're using PHP 5.3.x then you can install [password_compat](https://github.com/ircmaxell/password_compat) and it will work just fine.

Installation
============

To install, import the database content, then simply `require` the `accounts.php` file within your application. 

Usage
=====

See the example.php for various ways you can use the library.
