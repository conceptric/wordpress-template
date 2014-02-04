# WordPress Blog Template #

I created this template to simplify the deployment of WordPress to my own servers. This isn't the standard way to deploy WordPress, but it works for me, so if you want to use it do so at your own risk.

There are Capistrano based deployment tools and Rake based build tools in this template. You'll need to change a few variable names to suit your setup.

deploy.rb

* "blogname"
* "deploy-user"
* "git-server"

Also note that the paths might not match your preferred configuration.

production.rb and staging.rb

* "remote_server"
* the url for the primary domain