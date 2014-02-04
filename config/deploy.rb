set :stages, %w(staging production)
require 'capistrano/ext/multistage'           
require 'bundler/capistrano'
require 'cyberstruction_deploy'
require 'cyberstruction_wordpress'
require 'rvm/capistrano'

# Global deployment variables
set :application, "blogname"
set :local_config_path, File.dirname(__FILE__)
set :bundle_without, [:deployment]
set :rvm_type, :system
set :rvm_ruby_string, 'ruby-1.9.3-p484'

# Execution options and settings
set :user, "deploy-user"
ssh_options[:forward_agent] = true # Using SSH tunnelling
default_run_options[:pty] = true # To prevent the tty sudo error
set :use_sudo, true

# Source code access variables
set :scm, :git
set :deploy_via, :export
set :scm_username, user
set :servername, "git-server"
set :repository, "ssh://#{scm_username}@#{servername}/home/#{scm_username}/git/#{application}.git"
