# wotlk-premium-api
REST-API for RG Premium-Codes.
***

# Installation:
```sh
$ composer update
$ cd src
$ mv settings.php.dist settings.php
```

# Usage:
- Note that for all requests __Authorization Bearer abc123__, where __abc123__ is your private token, is required.
- Use __GET__ request https://yourdomain.com/get/__codetorequest__ to request code info.
- Use __POST__ request https://yourdomain.com/invalidate/__codetorequest__, to invalidate a code.