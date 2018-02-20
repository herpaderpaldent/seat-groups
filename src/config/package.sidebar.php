<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 11.02.2018
 * Time: 18:19
 */

return [
    'slackbot' => [
        'name'          => 'SeAT Groups',
        'icon'          => 'fa-group',
        'route_segment' => 'seatgroups',
        'entries' => [
            [
                'name'  => 'SeAT Groups',
                'icon'  => 'fa-gear',
                'route' => 'seatgroups.index',
            ],
            [
                'name'  => 'Add new SeAT Group',
                'icon'  => 'fa-plus-square',
                'permission' => 'Superuser',
                'route' => 'seatgroups.create',
            ]
        ]
    ]
];