# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.hostname = "atlas.dev"

  config.vm.box = "Ubuntu-12.04-omibus-chef"

  config.vm.box_url = "https://s3.amazonaws.com/gsc-vagrant-boxes/ubuntu-12.04-omnibus-chef.box"

  config.vm.network :private_network, ip: "33.33.33.10"
  config.vm.network :forwarded_port, guest: 3306, host: 3306

  config.ssh.max_tries = 40
  config.ssh.timeout   = 120

  config.vm.synced_folder "./", "/var/www/atlas", :owner => 'www-data', :group => "www-data"

  config.berkshelf.enabled = true

  config.vm.provision :chef_solo do |chef|
    chef.json = {
      :mysql => {
        :server_root_password => 'rootpass',
        :server_debian_password => 'debpass',
        :server_repl_password => 'replpass',
        :bind_address => '33.33.33.10',
        :allow_remote_root => true,
        :remove_test_database => true
      }
    }

    chef.run_list = [
        "recipe[atlas::default]"
    ]
  end
end
