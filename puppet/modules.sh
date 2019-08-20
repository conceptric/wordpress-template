#!/bin/sh
if [ command -v puppet > /dev/null ] 
then 
	exit 0
else
	puppet module install puppetlabs-apache --version 3.1.0
	puppet module install puppetlabs-mysql --version 5.4.0
fi