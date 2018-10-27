<?php

require_once __DIR__."/../bootstrap/web_init.php";

session_destroy();
header("Location: login.php?ref=logout&w=".urlencode(rstr(64)));
