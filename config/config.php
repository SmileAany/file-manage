<?php

return [
    'disk'      =>'public',
    'prefix'    =>'api/file',
    'middleware'=>[],
    'ext' => [
        'jpg', 'jpeg', 'png', 'xlsx', 'doc'
    ],
    'limit'=>100
];