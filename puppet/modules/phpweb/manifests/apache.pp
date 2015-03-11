class apache {
  package { "apache2":
    ensure  => present,
    require => Class["system-update"],
  }

  exec { "Enable mod_rewrite":
    command => "sudo a2enmod rewrite",
    require => Package["apache2"],
  } 

  service { "apache2":
    ensure  => "running",
    require => Exec["Enable mod_rewrite"],
  }

  file { 'remove default vhost':
    ensure    => absent,
    path      => "/etc/apache2/sites-enabled/000-default.conf",
    require   => Package["apache2"],
  }    

  file { "generate_vhost":
    notify    => Service["apache2"],
    ensure    => file,
    path      => "/etc/apache2/sites-enabled/app.conf",
    content   => template('phpweb/vhost.erb'),
    force     => true,
    owner     => "root",
    group     => "root",
    require   => File["remove default vhost"],
  }
}
