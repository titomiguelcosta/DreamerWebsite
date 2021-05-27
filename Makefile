deploy:
	ansible-playbook -i ansible/inventories/production/hosts ansible/webservers.yaml

debug:
	XDEBUG_CONFIG="idekey=PHPSTORM" PHP_IDE_CONFIG="serverName=127.0.0.1" php -dxdebug.remote_enable=1 -dxdebug.remote_autostart=1 -dxdebug.remote_host=127.0.0.1 -S localhost:9100 -t web/

