# seat-groups
With this [SeAT](https://github.com/eveseat/seat) Package you can create `auto`, `opt-in`, `managed` and `hidden` groups 
which correlate to SeAT-Roles.

[![Latest Stable Version](https://poser.pugx.org/herpaderpaldent/seat-groups/v/stable)](https://packagist.org/packages/herpaderpaldent/seat-groups)
[![StyleCI](https://github.styleci.io/repos/120507448/shield?branch=master)](https://github.styleci.io/repos/120507448)
[![Maintainability](https://api.codeclimate.com/v1/badges/ec32c88b40e1407ede38/maintainability)](https://codeclimate.com/github/herpaderpaldent/seat-groups/maintainability)
[![License](https://poser.pugx.org/herpaderpaldent/seat-groups/license)](https://packagist.org/packages/herpaderpaldent/seat-groups)
[![Total Downloads](https://poser.pugx.org/herpaderpaldent/seat-groups/downloads)](https://packagist.org/packages/herpaderpaldent/seat-groups)

***Important**: seat-groups are work in progress and certainly have som bugs
please do report any findings to seat-slack and report it as an issue*

## Installation

1. cd to `/var/www/seat`
2. enter `composer require herpaderpaldent/seat-groups`
4. run migration `php artisan migrate`

## Screenshots

![GroupSetup](https://i.imgur.com/jHAiAeW.png)

![Overview](https://i.imgur.com/1qh5RzD.png)

---

## SeAT Groups

### auto group
Members of set corporation are getting assigned the corresponding roles
![AutoGrouo](https://i.imgur.com/JE6DsWu.png)

### opt-in group
Members of set corporation can opt-in into a seat group and get then assigned
the corresponding roles
![OpenGroup](https://i.imgur.com/uRiTxN1.png)

### managed groups
Members may apply for these groups. Managers may accept or deny the application
![Managed](https://i.imgur.com/GcXv50A.png)

### hidden groups
This seat group is for hidden groups f.e. CEO. This group is only shown on the overview for users with `seatgroup.create` and `superuser` Permission.
![Hidden](https://i.imgur.com/f7Ry5SA.png)
---

## FAQ:

### Some Member does not get the roles he is supposed to
Make sure that all of your usergroups have a main_character set

### I just updated SeAT Groups but the fix is not working
Restart supervisor or `seat-worker` container to load the new code to workers.

### I have set an affiliation but it isn't shown
Make sure you are running the latest version. Later versions interfered with AdBlockers. Disable your adblocker temporally as work around and update.


# Donations

If you like SeAT Groups, i highly appreciate ISK Donations to Herpaderp Aldent.


