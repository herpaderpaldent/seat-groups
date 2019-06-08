# Version 1.7.2
Fix an issue with `SeatGroupsController` where an unused variable blocked the edit-view to be loaded.

# Version 1.7.1
This new version has two main purposes:
1. introduce testing
2. temporally disable group memberships

Firstly i have introduced testing to strengthen my confidence in seat-groups. Doing so, i am able to work on seat-groups with the confidence i need and assure not breaking anything.
Secondly, users now will lose a role gained through SeAT Group role temporally if the user group is missing a refresh token. As soon as its been regained the user group will regain the roles from its SeAT Groups he is member of and is qualified (meaning has valid affiliation) for.

# Version 1.7.0
This version allows the usage of seat-notifications 2.0. Be aware your subscriptions have been reset and you must resubscribe.

New features: 
* Identical Missing Refresh Token SeAT-Groups notifications will not be resend within 4hrs period.
* Identical Sync SeAT-Groups notifications will not be resend within 4hrs period.
* Identical Error SeAT-Groups notifications will not be resend within 4hrs period.

Fixes:
* SeAT-Group Application notification were previously not send out correctly to all groups administrators

# Version 1.6.9
Small improvement based on community feedback:

* SeAT Groups are now sort alphabetically instead of in order of creation thank you @eingang for suggesting
* Fixed a bug where managed groups did not show number of open applications on button. Thank you @warlof for reporting this
* Added other outstanding applications to discord and slack notification. Thank you @warlof for suggesting this feature.

# Version 1.6.8
This update adds application notification about candidates which are applying to a managed group. Works only for private channel notification to managers and superusers only.

This feature was suggested by @aaronmallen. Thank you all for providing me with all this valuable feedback and ideas on how to make SeAT Group great. 

# Version 1.6.7
This version replaces `SeATGroupNotification` models in:
* `GroupSyncFailedNotification` listener
* `MissingRefreshTokenNotification` listener
with correct `SeatNotificationRecipient` model.

Sorry about that and thank you @GoemFunaila for reporting this issue.

# Version 1.6.6
Small fix to select correct notification channel

# Version 1.6.5
This update takes advantage on the most recent seat-notification update (>1.1.0). Notifications may now be selected individually:
* SeatGroup Sync Event: Dispatched whenever a user group is attached or detached from a role
* SeATGroup Error Event: Dispatched whenever an error happens and laravel log should be investigated
* Missing Refresh Token Event: Dispatched every time SeAT Group finds a refresh token to be missing. Please note you can stop SeAT to notify you about a certain user that might have left your corporation or sold his character by deactivating the user (link is provided inside the notification to do so).

Furthermore:
* Fixed smaller typos
* Assure `eveseat/web ~3.0.14` is present
* Changed licence to MIT

# Version 1.6.4
* Include new seat plugin service pattern.
* Use proper form-id to subscribe to notifications

# Version 1.6.3
This version attempts to resolve major outages in using seat-groups >1.6.0 if not used with seat-notifications installed.

* Refactored GroupSync job to use events for missing refreshtoken, successful attaches/detached role updates and failed syncs
* Events have two listeners: one for writing a log entry and one to dispatch a notification
* `MissingRefreshTokenNotification` for discord contains a link to the configuration page of that user to quicker resolve false positive messages.

log events are queued into the default queue whereas notifications are prioritized. Thank you @Lawin for pointing out this bug.

# Version 1.6.2
Refactoring some of the logic regarding permission checks. 

# Version 1.6.1
This update brings some minor refactoring of some logic `beforeStart()` to better catch superusers and do not strip their roles.
Also notifications have been added for anyone using [seat-notifications](https://github.com/herpaderpaldent/seat-notifications)

You are going to be notified if a group receives new roles, if a group loses a role or an error happens:

# Version 1.6.0
This version is suited for the latest release of eveseat/web 3.0.10 and higher. SeAT now uses a newer version of datatables and therefore raw columns need to be enabled to show its html content.
Also the dependency on form generator in edit blade was removed.

# Version 1.5.2
This is a minor usability update. All of the found improvements are based from @Eingang's Feedback. Whenever you find any issues please don't hesitate to open an issue.
* Empty corporation affiliation request caused an error. This update adds validation to form request
* Added front end validation to corporation title affiliation 
* Corrected an error preventing to add multiple user groups to hidden seatgroup.

Thank you again for your feedback. 

# Version 1.5.1
This is a security update including some smaller improvements:
* MissingRefreshToken Exception removed, however potentially gained roles by missing character are seized until refresh token is provided. Thank you @fundaris for report this issue.
* Managed Group Members don't throw null-object exceptions anymore, if a main-character has not been set. (Thank you @warlof)
* Users without a main character set will be redirected to profile settings, by trying to access seat-groups index page.

# Version 1.5.0
Another major update on seat-groups. Containing many demanded features such as:
* Dispatching update job via web interface.
* Seat-Groups jobs are now dispatched on `high` queue (thanks @mmolitor87 for the suggestion).
* Clicking multiple times on `Add new SeAT Group` does not append multiple sets of roles anymore (thank you @MoucceuWildfire for reporting).
* SeAT-Groups now only logs attached and detached role events instead of every successful run (thank you @warlof for the idea).
* Deactivated users don't raise `MissingRefreshTokenExceptions` anymore
* `MissingRefreshTokenExceptions` are now more verbose.

As you can see many of the implemented features are based from your valuable input. Please don't hesitate to contact me IG or via [slack](https://eveseat-slack.herokuapp.com/) (join plugin channel) with anything you'd like to discuss.

# Version 1.4.2
* Fixxed a bug causing to show all hidden-groups, instead of only the hidden-groups where a user is a member of. Thank you @jediefe for reporting this.
* Removed raw:db statements from migrations (preparation for testing).
* Added badge to `manage_members` button to indicate members in waitlist. Thank you @Busterguy for suggesting. 

# Version 1.4.1
* missed a migration.

# Version 1.4.0
Introduction of Alliance Filter
* alliance filter
* styleCI Badge
* using `loadMigrationsFrom()` in ServiceProvider 
* refactored some classes for testing
* respect origin on SeAT-Group interaction

After this version `php artisan vendor:publish --force --all` will not be needed anymore. Migrations are directly loaded from the ServiceProvider. Finally you can add alliance filters. Finally if you are working on a SeAT-Group modal, accepting or denying members will return you to the modal instead of the default index file.

# Version 1.3.6
This update includes preparations for package-testing and better test-coverage, an improvement to `corporation_titles` table which caused users to lose corp titles in seat in some rare cases.

# Version 1.3.5
UI Improvements through introduction of nav-tabs and controller
* Improved SeatGroupController only returns seatgroups for which the user is allowed to see.
* Introduced Nav-tabs after community feedback of cluttered open- and manged groups (thanks @Anduriel) and some design ideas from @warlof.
* Update of README.md on github. Introduction of FAQ

# Version 1.3.4
Improvements on `GroupSync` and some UI improvements
* Merged pull request of @warlof aiming to improve `GroupSync` job with inconsistent user_groups. Namely when a main_character is missing (which should not happen anyway). Also some logging and tagging of the job was improved.
* Open-Group showed two join-buttons. Which is now fixed. Thank you @Anduriel for reporting this
* Action buttons in managed group modal collapsed in each other for large user_groups (many users). Bootstrap classes have been added to account for such cases. Thank you @Anduriel and @warlof for reporting this.

# Version 1.3.3
Some smaller improvements and bugfixes
* Improving `php artisan seat-groups:users:update --character_ids=ID` based on community feedback. Thank you @warlof for reporting this.
* Fixing a rare bug when an managed-group is transformed to an auto group, causing the Sync to fail. Thank you @scopeh for reporting this.
* changing `affiliation` to `/aff` to escape addblocker's filter.
* changing from POST to a GET for current corporation & corporation_titles affiliation
* changing from POST to a GET for corporation titles
* removing unnecessary routes.

# Version 1.3.2
Some bug fixes:
* adapting the /affiliation url because adblockers are acting up.
* bump version.

# Version 1.3.1
Smaller bugfixes
* removed typo in `isQualified()` method which was blocking all_corporations affiliation.
* removed some typos in changelog
* fixed detaching-error in `beforeStart()` method
* added SeAT Group as Manager in panel-footer.

# Version 1.3.0
This update is brought to you because and thanks to mmolitor87 & Vojax. Both had very valuable input on how to make SeAT-Groups even better. Thank you for being patient with me, and keep your feedback flowing.

* Now respecting all users in a user_group in `isQualified()` method
* You are able to setup a whole SeAT Group as manager of another SeAT Group
* Refactored edit-view for managed groups
* Refactored index-view for managed groups
* refactored `isManager()` method
* being more strict on membership 
* Changing Log from STRING to TEXT to accommodate longer messages.
* Attempting to modify LogController to resolve an issue with too large DBs
* `onFail` now correctly reports to log what went wrong

ATTENTION: Managers need now to be extra careful when purging members and make sure they purge all characters from a user_group, whereas before everything was bound to the main_character.
If a user lacks of at least 1 character in his user_group which qualifies in respective to your configured affiliation he will get removed from the SeAT group. This means the user will lose every role bound to this SeAT Group and needs to apply/opt-in again.


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
