<?php

function create_new_host($name){
	// Make dir with host files
	if(!file_exists("/var/www/html/{$name}")){
		exec("sudo mkdir /var/www/html/{$name}");
	}

	// Put new config file for virtual host
	$conf = "<VirtualHost *:80>
		ServerName {$name}

		ServerAdmin admin@{$name}
		DocumentRoot /var/www/html/{$name}
	" . '
		ErrorLog ${APACHE_LOG_DIR}/error.log
		CustomLog ${APACHE_LOG_DIR}/access.log combined
	</VirtualHost>';
	file_put_contents("/etc/apache2/sites-available/{$name}.conf", $conf);

	// Register local domen
	$host = "127.0.0.1\t{$name}\n";
	$hosts_file = file_get_contents("/etc/hosts");
	$hosts_file = $host . "\n" . $hosts_file;
	file_put_contents("/etc/hosts", $hosts_file);

	// Enable new virtual host
	exec("sudo a2ensite {$name}.conf");
	// Apache restart
	exec("sudo systemctl restart apache2");

	return true;
}

function process(){
	global $argv;
	$host_name = $argv[1];
	create_new_host($host_name);
	echo "Host {$name} was be created\n";
}

process();