<?php

return [
    'modules' => [
//        'SeoAgent' => [
//            'folder_path' => app_path('/Modules'), // root path to save the file, default is under your current local path: ../../app/
//            'namespace' => 'App\\Modules',  // class prefix namespace
//            'uri' => [
//                [
//                    'uri' => '/draft-data',
//                    'rules' => [
//                        'per_page' => 'integer',
//                        'page' => 'integer'
//                    ],
//                    'method' => 'get',
//                    'function' => 'getDraftData'
//                ],
//                [
//                    'uri' => '/draft-data/{id}',
//                    'rules' => [
//                        'draft_data' => 'json|required',
//                        'draft_data.meta.defaults.title' => 'string|nullable',
//                        'draft_data.meta.defaults.description' => 'string|nullable',
//                        'draft_data.meta.defaults.canonical' => 'string|nullable',
//                    ],
//                    'method' => 'put',
//                    'function' => 'updateDraftData'
//                ],
//                [
//                    'uri' => '/change-requests',
//                    'rules' => [
//                        'per_page' => 'integer',
//                        'page' => 'integer'
//                    ],
//                    'method' => 'get',
//                    'function' => 'getChangeRequests'
//                ],
//                [
//                    'uri' => '/change-requests/{hash_id}',
//                    'rules' => [
//                        'current_data' => 'json',
//                        'is_approved' => 'boolean'
//                    ],
//                    'method' => 'put',
//                    'function' => 'updateChangeRequests'
//                ],
//                [
//                    'uri' => '/change-requests',
//                    'rules' => [
//                        'current_data' => 'json|required',
//                        'current_data.meta.defaults.title' => 'string|nullable',
//                        'current_data.meta.defaults.description' => 'string|nullable',
//                        'current_data.meta.defaults.canonical' => 'string|nullable',
//                        'path' => 'string|required',
//                        'hash' => 'string|required',
//                    ],
//                    'method' => 'post',
//                    'function' => 'createChangeRequests'
//                ],
//                [
//                    'uri' => '/batch/change-requests',
//                    'rules' => [
//                        'data' => 'array'
//                    ],
//                    'method' => 'patch',
//                    'function' => 'bulkUpdateOrInsertChangeRequests'
//                ],
//            ],
//            'models' => [
//                'ChangeRequest','DraftData'
//            ]
//        ],
        'Setting' => [
            'enable' => true, // enable = true will generate the folder
            'folder_path' => app_path('/Modules'), // root path to save the file, default is under your current local path: ../../app/
            'namespace' => 'App\\Modules',  // class prefix namespace
            'uri' => [
                [
                    'uri' => '/push-settings',
                    'rules' => [
                    ],
                    'method' => 'get',
                    'function' => 'getPushSettings',
                    'type' => 'api'
                ],
                [
                    'uri' => '/pull-settings',
                    'rules' => [
                    ],
                    'method' => 'get',
                    'function' => 'getPullSettings',
                    'type' => 'api'
                ],
                [
                    'uri' => '/all-settings',
                    'rules' => [
                    ],
                    'method' => 'get',
                    'function' => 'getAllSettings',
                    'type' => 'web'
                ]
            ],
            'models' => [
                'PushSetting', 'PullSetting', 'AllSetting'
            ],
            'm' => [
                [
                    'name' => 'PushSetting',
                    'table' => ''
                ]
            ]
        ]
    ]

];
