<?php

// TODO: Mover la lógica de aspectRatio, aspectRatioUpsize y upsize dentro de Kameimage

$aspectRatio = function ($constraint){
    $constraint->aspectRatio();
    $constraint->upsize();
};

$aspectRatioUpsize = function ($constraint){
    $constraint->aspectRatio();
};

$upsize = function ($constraint){
    $constraint->upsize();
};

return [

    /*
    |--------------------------------------------------------------------------
    | Images Sizes
    |--------------------------------------------------------------------------
    |
    | From here you will be able to control the image formats you want for each entity and the transformations that will be applied for each format. 
    |
    | Each key must correspond to the name of an entity (usually the name of a table).
    | Inside, each key will correspond to the format / size of the image.
    |
    | //TODO: continuar documentación
    |
    */

    'sizes' => [
        'products' => [
            'thumb' => [
                'fit' => [220, 220]
            ],
            'md' => [
                'fit' => [1024, 768]
            ],
            'md-mobile' => [
                'fit' => [600, 400]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | The name of the queue that will be in charge of applying the transformations 
    |
    */

    'queue' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | TODO: continuar documentación
    |
    */

    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
        'default_folder' => 'originals',
        'add_folder_day' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image not found
    |--------------------------------------------------------------------------
    |
    | TODO:
    |
    */

    'image_not_found' => [
        'default' => 'imagen-no-disponible.jpg',
        'admins' => 'user.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Thumb
    |--------------------------------------------------------------------------
    |
    | TODO:
    |
    */

    'thumb' => [
        'defualt' => 'thumb',
        'products' => 'md',
    ],

    /*
    |--------------------------------------------------------------------------
    | Breakpoints
    |--------------------------------------------------------------------------
    |
    | TODO:
    |
    */

    'breakpoints' => [
        'products' => [
            'thumb' => false,
            'md' => [
                'sources' => [
                    [
                        'media_query' => 'min-width: 650px',
                        'srcset_folder' => 'md-mobile'
                    ],
                    [
                        'media_query' => 'max-width:649px',
                        'srcset_folder' => 'md'
                    ]
                ]
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | TODO:
    |
    */

    'routes' => [
        'prefix' => 'kameimage',
        'name' => 'kameimage',
        'middlewares' => 'web,auth'
    ],

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    |
    | TODO:
    |
    */

    'form' => [
        'images' => 'images',
        'entity' => 'entity',
        'image_id' => 'image_id',
    ],
];
