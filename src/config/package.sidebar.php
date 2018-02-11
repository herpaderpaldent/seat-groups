<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 11.02.2018
 * Time: 18:19
 */

return [
    'slackbot' => [
        'name'          => 'Seat Groups',
        'icon'          => 'fa-group',
        'route_segment' => 'seatgroups',
        'entries' => [
            [
                'name'  => 'Seat Group Management',
                'icon'  => 'fa-gear',
                'route' => 'seatgroups.index',
            ]
        ]
    ]
];