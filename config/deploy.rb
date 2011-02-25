require 'rubygems'
require 'capcake'

set :user, 'devftp'
set :application, 'atlas' # Your app's location (domain or sub-domain name)
set :deploy_to, "/var/www/vhosts/dev.ctsfla.com/atlas"
#set :default_shell, '/bin/bash'
#default_run_options[:pty] = true
#default_environment['PATH'] = "/var/www/vhosts/dev.ctsfla.com/atlas/cakephp/cake/console:$PATH"
server "dev.ctsfla.com:22", :web, :db, :primary => true
ssh_options[:forward_agent] = true
#ssh_options[:keys] = %w('C:\Users\dnolan.CTS\.ssh\id_rsa')
set :use_sudo, false
set :keep_releases, 2

set :repository, "git@git.assembla.com:CTSATLAS.git"
set :branch, 'staging'

# Cake Settings
set :cake_branch, "master"

task :finalize_deploy, :roles => [:web] do
run "chmod 755 -R #{release_path}"
run "mv #{release_path}/webroot/index_staging.php #{release_path}/webroot/index.php"
run "ln -s #{shared_path}/plugins #{current_release}/plugins"

end

# remove the .git directory and .gitignore from the current release
desc "Remove git directories from release"
task :remove_git_directories, :roles => [:web] do
run "rm -rfd #{release_path}/.git"
run "rm #{release_path}/.gitignore"
end

desc "Update database schema"
task :migrate_database, roles => [:web] do
## FIX THIS TO DO DATABASE SCHEMA
run "cd #{current_release} && cake schema create danielSchema < prompt.txt"
##run "cd #{current_release} && cake schema update danielSchema << EOF y EOF"
end


after "deploy:update_code", :finalize_deploy
after "deploy:update_code", :remove_git_directories

capcake