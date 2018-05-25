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
                'uses' => 'SeatGroupsController@store'
            ]);
            Route::delete('/{group_id}', [
                'as' => 'seatgroups.destroy',
                'middleware' => 'bouncer:superuser',
                'uses' => 'SeatGroupsController@destroy'
            ]);
            Route::post('/{group_id}/corporation', [
                'as' => 'seatgroupcorporation.update',
                'uses' => 'SeatGroupCorporationController@update'
            ]);
            Route::delete('/{group_id}/corporation/{corporation_id}', [
                'as' => 'seatgroupcorporation.destroy',
                'uses' => 'SeatGroupCorporationController@destroy'
            ]);
            Route::post('/{group_id}/user', [
                'as' => 'seatgroupuser.update',
                'uses' => 'SeatGroupUserController@update'
            ]);
            Route::delete('/{group_id}/user', [
                'as' => 'seatgroupuser.destroy',
                'uses' => 'SeatGroupUserController@destroy'
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


