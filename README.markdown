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

Finally, you'll need to set up the file 'shared/assets/wp-config.php' to reflect your local development preferences. It should be noted that nothing in the shared directory is used in production.

I use Bundler so the sequence goes something like this. First install the gems in a local vendor directory.

    bundle install --path vendor
    
Then run a rake command to generate the development directory structure and template configuration files (you edit these later).
    
    bundle exec rake setup
    
Finally,
    
    bundle exec rake -T
    
should tell you where to go from here.