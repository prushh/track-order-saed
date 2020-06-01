<?php
// Initialize session
session_start();

// Destroy session and variables
session_destroy();

// Redirect to index
header('Location: index.php', true, 302);
exit(0);