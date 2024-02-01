<?php
// outputs the username that owns the running php/httpd process
// (on a system with the "whoami" executable in the path)
$output=null;
$retval=null;
exec('git reset --hard', $output, $retval);
echo "$output\n";
exec('git pull origin main', $output, $retval);
echo "$output\n";
exec('composer install', $output, $retval);
echo "Returned with status $retval and output:\n";
print_r($output);
?>