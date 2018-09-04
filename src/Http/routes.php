<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 11.02.2018
 * Time: 16:57
 */

Route::group([
    'namespace' => 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers',
    'prefix' => 'seatgroups'
    ], function() {

        // General Group of Links open for Everyone
        Route::group([
            'middleware' => ['web', 'auth']
        ], function (){
            Route::get('/', [
                'as'   => 'seatgroups.index',
                'uses' => 'SeatGroupsController@index',
                'middleware' => 'bouncer:seatgroups.view'
                ]);
            Route::get('/about', [
                'as'   => 'seatgroups.about',
                'middleware' => 'bouncer:seatgroups.view',
                'uses' => 'SeatGroupsController@about'
            ]);

            Route::group([
                'namespace' => 'Logs',
                'prefix' => 'logs'
            ], function () {
                Route::get('/logs', [
                    'as' => 'logs.get',
                    'middleware' => 'bouncer:seatgroups.edit',
                    'uses' => 'SeatGroupLogsController@getSeatGroupLogs'
                ]);
                Route::get('/logs/deletebutton', [
                    'as' => 'logs.get.delete.button',
                    'middleware' => 'bouncer:seatgroups.edit',
                    'uses' => 'SeatGroupLogsController@getSeatGroupDeleteButton'
                ]);
                Route::get('/logs/truncate', [
                    'as' => 'logs.truncate',
                    'middleware' => 'bouncer:seatgroups.edit',
                    'uses' => 'SeatGroupLogsController@truncateSeatGroupLogs'
                ]);
            });

            }
        );
        // Admin Route
        Route::group([
            'middleware' => ['web', 'auth']
        ], function (){
            Route::get('/{group_id}/edit', [
                'as'   => 'seatgroups.edit',
                'uses' => 'SeatGroupsController@edit'
                ]);
            Route::get('/create', [
                'as' => 'seatgroups.create',
                'uses' => 'SeatGroupsController@create'
                ]);

            Route::post('/{group_id}', [
                'as' => 'seatgroups.update',
                'uses' => 'SeatGroupsController@update'
            ]);
            Route::post('/', [
                'as' => 'seatgroups.store',
                'uses' => 'SeatGroupsController@store'
            ]);
            Route::post('/{group_id}/delete', [
                'as' => 'seatgroups.destroy',
                'middleware' => 'bouncer:superuser',
                'uses' => 'SeatGroupsController@destroy'
            ]);
            Route::post('/{group_id}/corporation', [
                'as' => 'seatgroups.add.corp.affiliation',
                'middleware' => 'bouncer:seatgroups.create',
                'uses' => 'SeatGroupsController@addAffilliation'
            ]);
            Route::post('/{seat_group_id}/corporation/{corporation_id}/remove', [
                'as' => 'seatgroups.remove.corp.affiliation',
                'middleware' => 'bouncer:seatgroups.create',
                'uses' => 'SeatGroupsController@removeAffiliation'
            ]);
            Route::post('/{group_id}/user', [
                'as' => 'seatgroupuser.update',
                'uses' => 'SeatGroupUserController@update'
            ]);
            Route::delete('/{group_id}/user', [
                'as' => 'seatgroupuser.destroy',
                'uses' => 'SeatGroupUserController@destroy'
            ]);
            Route::delete('/{seat_group_id}/user/{group_id}', [
                'as' => 'seatgroupuser.removeGroupFromSeatGroup',
                'middleware' => 'bouncer:seatgroups.create',
                'uses' => 'SeatGroupUserController@removeGroupFromSeatGroup'
            ]);
            Route::post('/{group_id}/manager', [
                'as' => 'seatgroupuser.addmanager',
                'uses' => 'SeatGroupUserController@addManager'
            ]);
            Route::delete('/{seat_group_id}/manager/{group_id}', [
                'as' => 'seatgroupuser.removemanager',
                'uses' => 'SeatGroupUserController@removeManager'
            ]);
            Route::delete('/{seat_group_id}/member/{group_id}', [
                'as' => 'seatgroupuser.removemember',
                'uses' => 'SeatGroupUserController@removeMember'
            ]);
            Route::post('/{seat_group_id}/member/{group_id}', [
                'as' => 'seatgroupuser.acceptmember',
                'uses' => 'SeatGroupUserController@acceptApplication'
            ]);
        }

        );

    }
);


