<?php
// VULN-INFO-002: Sensitive Files Exposed
// File testing yang ditinggalkan developer
echo "Server Uname: " . php_uname() . "<br>";
echo "PHP SAPI: " . php_sapi_name() . "<br>";
echo "Current User: " . get_current_user();
