class php5 {
  package { "php5":
    ensure => latest,
    require => Class["system-update"],
  }

  package { ['php5-gd', 'php5-mcrypt', 'php5-mysql', 'php5-curl']:
    ensure => latest,
    require => Package["php5"],
  }

  exec { "Enable php5-mcrypt":
    command => "sudo php5enmod mcrypt",
    require => Package["php5-mcrypt"],
    notify  => Service["apache2"],
  } 
}
