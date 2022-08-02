<?php

return [
    "router" => [
        "default" => [
            "options" => [
                "prefix" => "/"
            ],
            "middleware" => [
                "public" => [],
                "private" => ['auth', 'role:admin|sales'],
                "api/admin" => ['auth', 'role:admin|sales'],
                "admin" => ['auth', 'role:admin|sales'],
            ]
        ]
    ]
];