<?php
require_once 'config.php';

// Logout user
session_destroy();
session_start();

redirect(SITE_URL . '/index.php');
