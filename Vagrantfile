# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = 'web.developer.dev'
  config.vm.network :forwarded_port, guest: 80, host: 4545
  config.vm.network :forwarded_port, guest: 9292, host: 4646

  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
    v.cpus = 1
  end

  config.vm.synced_folder "public/", "/var/www/app", 
    owner: "www-data", group: "www-data"

  config.vm.provision :puppet do |puppet|
    puppet.options = ['--verbose']
    puppet.manifests_path = "puppet/manifests"
    puppet.module_path = "puppet/modules"
    puppet.manifest_file  = "default.pp"
  end
  
end
