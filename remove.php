<?php

function remove_host($name){
	// Remove dir
	if(file_exists("/var/www/html/{$name}")){
		exec("sudo rm -rf /var/www/html/{$name}");
	}

	// Remove virtual host
	if(file_exists("/etc/apache2/sites-available/{$name}.conf")){
		exec("sudo rm /etc/apache2/sites-available/{$name}.conf");
	}

	// Unregister local domen
	$host = "127.0.0.1\t{$name}\n";
	$hosts_file = file_get_contents("/etc/hosts");
	$hosts_file = str_replace($host, '', $hosts_file);
	file_put_contents("/etc/hosts", $hosts_file);

	// Apache restart
	exec("sudo systemctl restart apache2");

	return true;
}

function process(){
	global $argv;
	$host_name = $argv[1];
	$answ = readline("Are you sure? [y/n]: ");
	if($answ == strtolower('y')){
		remove_host($host_name);
		echo "Host {$host_name} was be removed\n";
	}
}

process();