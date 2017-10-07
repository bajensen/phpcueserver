<?php

return [
    'server' => 'http://cue-serer-ip-or-host',
    'general' => [
        'on' => 'b1>8@0; ',
        'off' => 'c1>3@0; b1>8@0; '
    ],
    'switches' => [
        'white' => [
            'on' => 'q4g; b1@fl',
            'off' => '',
            'status' => 'button1'
        ],
        'red' => [
            'on' => 'q1g; b2@fl',
            'off' => '',
            'status' => 'button2'
        ],
        'green' => [
            'on' => 'q2g; b3@fl',
            'off' => '',
            'status' => 'button3'
        ],
        'blue' => [
            'on' => 'q3g; b4@fl',
            'off' => '',
            'status' => 'button4'
        ],
        'yellow' => [
            'on' => 'q6g; b5@fl',
            'off' => '',
            'status' => 'button5'
        ],
        'purple' => [
            'on' => 'q5g; b6@fl',
            'off' => '',
            'status' => 'button6'
        ],
        'cyan' => [
            'on' => 'q7g; b7@fl',
            'off' => '',
            'status' => 'button7'
        ],
        'fade' => [
            'on' => 'q10g; b8@fl',
            'off' => '',
            'status' => 'button8'
        ],
        'use_fade' => [
            'on' => 'start p l',
            'off' => 'stop p 1',
            'status_method' => 'playback_run_mode',
            'use_general' => false,
        ],
        'backlight_bright' => [
            'on' => '"_backlight"=FL',
            'off' => '"_backlight"=1',
            'status_method' => 'cache',
            'use_general' => false,
        ],
    ]
];
