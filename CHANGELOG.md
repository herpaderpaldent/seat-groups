# Version 1.2.0
This is a major update to SeAT-Groups as it introduces many asked features and refactoring lots of front end mistakes i made. This is just an initial working release. I will continue to improve SeAT Groups and refactor even classes i have introuced with this release and i see room for improvement.

* Introducing `Corporation Title Filter`
* Refactor affiliation box on `edit`
** Introducing of actions and custom validation for corporation-titles
** Serverside processed affiliation-table
** Logic refactoring to prevent assigning of corp-title affiliation whilst there is corp affiliated
** Logic introduction to prevent assigning other corporation wit `All Corporations` option enabled

* Refactor `index`-page
** Removing of laravel-form-builder inside
** Refactoring of many routes.
** split view in many partials to optimize maintainability.
** Reworked `managed` SeAT group modal: using `Datatables` asynchronously and bigger buttons.

* Refactor of `SeatGroups`
** Introducing of `isQualified()` method
** `GroupSync` will take use of this method.
** Refactoring of `isMember()` function
** Reducing of switch-complexity in `GroupSync`


# Version 1.1.1
Fix changed class name.

# Version 1.1.0
This release consist of many improvements:
* Introducing queueable SeAT-Groups update jobs. 
* Remove blocking conditions if ons user group fails.
* Roles are being removed from user groups with missing RefreshToken.
* Introducing `RefreshTokenObvserver`: upon deletion a new `GroupSync` is dispatched.
* Introducing Logs in about-view.
* `seat-groups:users:update` now accepts a list of character_ids to individually update.

This means every time the scheduled update `seat-groups:users:update` runs, every user group gets updated as an individual job, which is tracked and observable in Horizon dashboard. If the update job fails, the error can been seen in the workers dashboard. This further means, if one user group fails others are not blocked anymore.
Thanks to some refactoring and a new observer, user groups with a missing RefreshToken are getting striped from their roles until every character in SeAT has a valid RefreshToken again.
Thanks to Logs every Sync is getting logged and is viewable on the about-view, for anyone with the `seatgroups.create` permission.
Finally with `seat-groups:users:update --character_ids=123456789` you are able to dispatch an update job for the given character's SeAT group.

Thanks at this point to @warlof and his two packages https://github.com/warlof/slackbot and https://github.com/warlof/seat-discord-connector without him and his packages (which btw. integrate very well with SeAT-Groups) i wouldn't have been able to write all the job-logic.


# Version 1.0.0
* Stable release.
* Everything now works stable.

I seek to implement further functionalities, refactor some jobs and extend further the affiliation for eligibility (f.e. Corporation Titles). Other then that: SeAT Groups will be included in SeAT-Core with the next major release (tagged 3.1).

# Version 0.9.8
* Release Candidate for Version 1.0.0
* Introducing About-Section

# Version 0.9.7
* Introducing per User Role Sync
* Dispatching user role sync when opt-in happens
* Worked through the ToDo's in code
* Fixed isMember() function for AllCorporation SeAT Groups
