<?php

return [
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    
    'owner' => [
        'driver' => 'session',
        'provider' => 'owners',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    
    'owners' => [
        'driver' => 'eloquent',
        'model' => App\Models\Owner::class,
    ],
],
];
