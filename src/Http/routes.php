<?php
/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 11.02.2018
 * Time: 16:57.
 */
Route::group([
    'namespace'  => 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers',
    'prefix'     => 'seatgroups',
    'middleware' => ['web', 'auth', 'bouncer:seatgroups.view'],
], function () {

    Route::get('/', [
        'as'   => 'seatgroups.index',
        'uses' => 'SeatGroupsController@index',
    ]);
    Route::get('/about', [
        'as'   => 'seatgroups.about',
        'uses' => 'SeatGroupsController@about',
    ]);
    Route::post('/{group_id}/user', [
        'as'   => 'seatgroups.user.join',
        'uses' => 'SeatGroupUserController@update',
    ]);
    Route::get('/{group_id}/user', [
        'as'   => 'seatgroups.user.leave',
        'uses' => 'SeatGroupUserController@destroy',
    ]);
    Route::get('/{group_id}/member', [
        'as'   => 'seatgroups.get.members.table',
        'uses' => 'SeatGroupUserController@getMembersTable',
    ]);
    Route::post('/{group_id}/member/accept', [
        'as'   => 'seatgroups.accept.member',
        'uses' => 'SeatGroupUserController@acceptApplication',
    ]);

    // Routes for creating.
    Route::group([
        'middleware' => ['bouncer:seatgroups.create'],
    ], function () {

        Route::get('/create', [
            'as'   => 'seatgroups.create',
            'uses' => 'SeatGroupsController@create',
        ]);
        Route::post('/manager/add', [
            'as'   => 'seatgroupuser.addmanager',
            'uses' => 'SeatGroupUserController@addManager',
        ]);
        Route::post('/manager/remove', [
            'as'   => 'seatgroupuser.remove.manager',
            'uses' => 'SeatGroupUserController@removeManager',
        ]);
        Route::get('/update', [
            'as'   => 'seatgroup.user.update',
            'uses' => 'SeatGroupsController@dispatchUpdate',
        ]);

    });

    // Routes for New Affiliation
    Route::group([
        'namespace' => 'Affiliation',
        'prefix' => 'aff',
        'middleware' => ['bouncer:seatgroups.create'],
    ], function () {

        Route::get('/corporation_titles/', [
            'as'    => 'affiliation.resolve.corporation.title',
            'uses'  => 'SeatGroupCorporationTitleController@getCorporationTitles',
        ]);
        Route::post('/add/corporation_title', [
            'as'    => 'affiliation.add.corporation.title.affiliation',
            'uses'  => 'SeatGroupCorporationTitleController@addCorporationTitleAffiliation',
        ]);
        Route::get('/current', [
            'as'    => 'affiliation.get.current.affiliations',
            'uses'  => 'SeatGroupAffiliationController@getCurrentAffiliations',
        ]);
        Route::post('/remove/all_corporations', [
            'as'    => 'affiliation.remove.all.corporation',
            'uses'  => 'SeatGroupCorporationController@removeAllCorporations',
        ]);
        Route::post('/remove/corporation', [
            'as'    => 'affiliation.remove.corporation',
            'uses'  => 'SeatGroupCorporationController@removeCorporation',
        ]);
        Route::post('/remove/corporation_title', [
            'as'    => 'affiliation.remove.corporation.title',
            'uses'  => 'SeatGroupCorporationTitleController@removeCorporationTitleAffiliation',
        ]);
        Route::post('/add/corporation', [
            'as'    => 'affiliation.add.corp.affiliation',
            'uses'  => 'SeatGroupCorporationController@addCorporationAffiliation',
        ]);
        Route::post('/add/alliance', [
            'as'    => 'affiliation.add.alliance.affiliation',
            'uses'  => 'SeatGroupAllianceController@addAllianceAffiliation',
        ]);
        Route::post('/remove/alliance', [
            'as'    => 'affiliation.remove.alliance.affiliation',
            'uses'  => 'SeatGroupAllianceController@removeAllianceAffiliation',
        ]);

    });

    // Routes for SeatgroupsLogs
    Route::group([
        'namespace'  => 'Logs',
        'prefix'     => 'logs',
        'middleware' => 'bouncer:seatgroups.create',
    ], function () {

        Route::get('/logs', [
            'as'   => 'logs.get',
            'uses' => 'SeatGroupLogsController@getSeatGroupLogs',
        ]);
        Route::get('/logs/deletebutton', [
            'as'   => 'logs.get.delete.button',
            'uses' => 'SeatGroupLogsController@getSeatGroupDeleteButton',
        ]);
        Route::get('/logs/truncate', [
            'as'   => 'logs.truncate',
            'uses' => 'SeatGroupLogsController@truncateSeatGroupLogs',
        ]);
    });

    // Routes for Seatgroup Notifications
    Route::group([
        'namespace'  => 'Notifications',
        'prefix'     => 'notifications',
        'middleware' => 'bouncer:seatgroups.create',
    ], function () {

        // SeatGroupSync
        Route::get('/seatgroup_sync/{via}/subscribe/private', [
            'as'   => 'seatnotifications.seatgroup_sync.subscribe.user',
            'uses' => 'SeatGroupSyncController@subscribeDm',
        ]);

        Route::get('/seatgroup_sync/{via}/unsubscribe/private', [
            'as'   => 'seatnotifications.seatgroup_sync.unsubscribe.user',
            'uses' => 'SeatGroupSyncController@unsubscribeDm',
        ]);

        // SeatGroupError
        Route::get('/seatgroup_error/{via}/subscribe/private', [
            'as'   => 'seatnotifications.seatgroup_error.subscribe.user',
            'uses' => 'SeatGroupErrorController@subscribeDm',
        ]);

        Route::get('/seatgroup_error/{via}/unsubscribe/private', [
            'as'   => 'seatnotifications.seatgroup_error.unsubscribe.user',
            'uses' => 'SeatGroupErrorController@unsubscribeDm',
        ]);

        // SeatGroupMissingRefreshToken
        Route::get('/missing_refreshtoken/{via}/subscribe/private', [
            'as'   => 'seatnotifications.missing_refreshtoken.subscribe.user',
            'uses' => 'MissingRefreshTokenController@subscribeDm',
        ]);

        Route::get('/missing_refreshtoken/{via}/unsubscribe/private', [
            'as'   => 'seatnotifications.missing_refreshtoken.unsubscribe.user',
            'uses' => 'MissingRefreshTokenController@unsubscribeDm',
        ]);

        // SeatGroupApplication
        Route::get('/seatgroup_application/{via}/subscribe/private', [
            'as'   => 'seatnotifications.seatgroup_application.subscribe.user',
            'uses' => 'SeatGroupApplicationController@subscribeDm',
        ]);

        Route::get('/seatgroup_application/{via}/unsubscribe/private', [
            'as'   => 'seatnotifications.seatgroup_application.unsubscribe.user',
            'uses' => 'SeatGroupApplicationController@unsubscribeDm',
        ]);

        Route::group([
            'middleware' => ['bouncer:seatnotifications.configuration'],
        ], function () {

            // SeatGroupSync
            Route::post('/seatgroup_sync/channel', [
                'as'   => 'seatnotifications.seatgroup_sync.subscribe.channel',
                'uses' => 'SeatGroupSyncController@subscribeChannel',
            ]);

            Route::get('/seatgroup_sync/{via}/unsubscribe', [
                'as'   => 'seatnotifications.seatgroup_sync.unsubscribe.channel',
                'uses' => 'SeatGroupSyncController@unsubscribeChannel',
            ]);

            // SeatGroupError
            Route::post('/seatgroup_error/channel', [
                'as'   => 'seatnotifications.seatgroup_error.subscribe.channel',
                'uses' => 'SeatGroupErrorController@subscribeChannel',
            ]);

            Route::get('/seatgroup_error/{via}/unsubscribe', [
                'as'   => 'seatnotifications.seatgroup_error.unsubscribe.channel',
                'uses' => 'SeatGroupErrorController@unsubscribeChannel',
            ]);

            // SeatGroupError
            Route::post('/missing_refreshtoken/channel', [
                'as'   => 'seatnotifications.missing_refreshtoken.subscribe.channel',
                'uses' => 'MissingRefreshTokenController@subscribeChannel',
            ]);

            Route::get('/missing_refreshtoken/{via}/unsubscribe', [
                'as'   => 'seatnotifications.missing_refreshtoken.unsubscribe.channel',
                'uses' => 'MissingRefreshTokenController@unsubscribeChannel',
            ]);
        });

    });

    // TODO Cleanup the legacy routes from prior 1.1.0
    Route::group([
        'middleware' => ['web', 'auth'],
    ], function () {

        Route::get('/{group_id}/edit', [
            'as'   => 'seatgroups.edit',
            'uses' => 'SeatGroupsController@edit',
        ]);

        Route::post('/{group_id}', [
            'as'   => 'seatgroups.update',
            'uses' => 'SeatGroupsController@update',
        ]);
        Route::post('/', [
            'as'   => 'seatgroups.store',
            'uses' => 'SeatGroupsController@store',
        ]);
        Route::post('/{group_id}/delete', [
            'as'         => 'seatgroups.destroy',
            'middleware' => 'bouncer:superuser',
            'uses'       => 'SeatGroupsController@destroy',
        ]);
        Route::post('/{seat_group_id}/corporation/{corporation_id}/remove', [
            'as'         => 'seatgroups.remove.corp.affiliation',
            'middleware' => 'bouncer:seatgroups.create',
            'uses'       => 'SeatGroupsController@removeAffiliation',
        ]);

        Route::post('/{seat_group_id}/user/{group_id}', [
            'as'         => 'seatgroupuser.removeGroupFromSeatGroup',
            'middleware' => 'bouncer:seatgroups.create',
            'uses'       => 'SeatGroupUserController@removeGroupFromSeatGroup',
        ]);
        Route::delete('/{seat_group_id}/member/{group_id}', [
            'as'   => 'seatgroupuser.removemember',
            'uses' => 'SeatGroupUserController@removeMember',
        ]);
        Route::post('/{seat_group_id}/member/{group_id}', [
            'as'   => 'seatgroupuser.acceptmember',
            'uses' => 'SeatGroupUserController@acceptApplication',
        ]);

    });

});
