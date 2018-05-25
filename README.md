# seat-groups
With this SeAT Package you can create `auto`, `opt-in` and `managed` groups 
which correlate to SeAT-Roles.

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

### managed groups
Members may apply for these groups. Managers may accept or deny the application

## Screenshots

![GroupSetup](https://i.imgur.com/7qElUyB.png)

![Overview](https://i.imgur.com/Yo6Ugyk.png)
![Managed](https://i.imgur.com/mYB30rZ.png)