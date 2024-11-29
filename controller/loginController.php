<?php

require_once dirname(__DIR__) . "../.config/db-connection.php";

echo dbConnection($dsn, $username, $password);