<?php

return [
    /**
     * Specify Node Api URL here to post data to that URL.
     */
    "push_user" => "localhost/user/push",
    "push_global" => "localhost/global/push",

    /**
     * Rethink DB configurations.
     */
    "rethink_db" => [
        "options" => [
            "host" => "localhost",
            "user" => "username",
            "password" => "password"
        ],
        "port" => "port",
        "db" => "db_name"
    ],
];