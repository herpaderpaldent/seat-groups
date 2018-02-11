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
        'icon'          => 'fa-slack',
        'route_segment' => 'seatgroups',
        'entries' => [
            [
                'name'  => 'Slack Access Management',
                'icon'  => 'fa-shield',
                'route' => 'seatgroups.index',
            ]
        ]
    ]
];