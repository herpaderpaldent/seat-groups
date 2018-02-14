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
            'middleware' => 'web'
        ], function (){
            Route::get('/', [
                'as'   => 'seatgroups.index',
                'uses' => 'SeatGroupsController@index'
                ]);
            }
        );
        // Admin Route
        Route::group([
            'middleware' => ['web','bouncer:superuser']
        ], function (){
            Route::get('/edit/{group_id}', [
                'as'   => 'seatgroups.edit',
                'uses' => 'SeatGroupsController@edit'
                ]);

            Route::get('/new', [
                'as'   => 'seatgroups.new',
                'uses' => 'SeatGroupsController@new'
                ]);
            }
        );
    }
);
