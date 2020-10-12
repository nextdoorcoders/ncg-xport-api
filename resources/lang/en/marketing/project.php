<?php

return [
    'validation' => [
//        'name.required'                       => '',
//        'name.max'                            => '',
//        'is_enabled.boolean'                  => '',
//        'organization_id.exists'              => '',
//        'date_start_at.date'                  => '',
//        'date_start_at.date_format'           => '',
//        'date_start_at.before'                => '',
//        'date_end_at.date'                    => '',
//        'date_end_at.date_format'             => '',
//        'date_end_at.after'                   => '',
        'account_id.required'                 => 'Marketing account is required',
        'parameters.account_id.required'      => 'Account ID is required',
        'parameters.developer_token.required' => 'Developer token is required',
    ],
];
