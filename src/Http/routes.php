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
    Route::get('test', [
        'as'   => 'seatgroups.index',
        'uses' => 'SeatGroupsController@get_index'
    ]);
    Route::get('bar', 'SeatGroupsController@get_index2');

    Route::get('foo', function () {
        return 'Hello World';
    });
}
);
