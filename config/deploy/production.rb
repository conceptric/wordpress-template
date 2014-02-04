# PRODUCTION-specific deployment configuration
# please put general deployment config in config/deploy.rb
set :remote_host_name, "remote_server"
set :domain_user, "#{user}"
set :deploy_to, "/home/#{domain_user}/public_html/#{application}"
set :branch, "master"

# Defining roles
role :web, remote_host_name
role :db,  remote_host_name, :primary => true

set :primary_app_domain_name, "#{application}.co.uk"
