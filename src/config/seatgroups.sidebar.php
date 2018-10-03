<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 11.02.2018
 * Time: 18:19.
 */

return [
    'slackbot' => [
        'name'          => 'SeAT Groups',
        'icon'          => 'fa-group',
        'route_segment' => 'seatgroups',
        'permission'    => 'seatgroups.view',
        'entries' => [
            [
                'name'  => 'SeAT Groups',
                'icon'  => 'fa-gear',
                'route' => 'seatgroups.index',
                'permission'    => 'seatgroups.view',
            ],
            [
                'name'  => 'Add new SeAT Group',
                'icon'  => 'fa-plus-square',
                'permission' => 'seatgroups.create',
                'route' => 'seatgroups.create',
            ],
            [
                'name'  => 'About',
                'icon'  => 'fa-info-circle',
                'permission' => 'seatgroups.view',
                'route' => 'seatgroups.about',
            ],
        ],
    ],
];
