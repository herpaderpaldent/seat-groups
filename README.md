# seat-groups
With this SeAT Package you can create `auto` and `opt-in` groups 
which correlate to SeAT-Role.

***Important**: seat-groups are work in progress and certainly have som bugs
please do report any findings to seat-slack and report it as an issue*

## Installation

1. cd to `/var/www/seat`
2. enter `composer require herpaderpaldent/seat-groups`
3. Publish `php artisan vendor:publish --force --all`
4. run migration `php artisan migrate`


### auto group
Members of set corporation are getting assigned the corresponding roles

### opt-in group
Members of set corporation can opt-in into a seat group and get then assigned
the corresponding roles

## Screenshots

![GroupSetup](https://i.imgur.com/7qElUyB.png)

![Overview](https://i.imgur.com/Yo6Ugyk.png)