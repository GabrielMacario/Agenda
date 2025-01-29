<?php

    $BASE_URL = "http://". $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI'] . '?') . '/';

    echo $BASE_URL;