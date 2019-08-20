class { 'apache':
  mpm_module => 'prefork',
  default_vhost => false,
}

apache::vhost { 'awordpress':
  port    		=> '80',
  docroot 		=> '/var/www/app',
  servername 	=> 'localhost',
  options       => ['FollowSymLinks'],
}

class { '::apache::mod::rewrite': }

class { '::apache::mod::php': 
  php_version => '7.2',
}

package { [ 'php-curl', 'php-gd', 'php-mbstring', 'php-xml', 'php-xmlrpc', 'php7.2-mysql' ]:
  ensure => installed,
  notify => Service['apache2'],
}

class { '::mysql::server':
  root_password           => 'apassword',
  remove_default_accounts => true,
}

class { '::mysql::bindings':
  php_enable => true,
}

mysql::db { 'wp_template':
  user     => 'webdeveloper',
  password => 'password',
  host     => 'localhost',
  grant    => ['ALL'],
}