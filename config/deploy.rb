require 'rubygems'
require 'capcake'

set :application, 'atlas' # Your app's location (domain or sub-domain name)
set :repository, "git@git.assembla.com:CTSATLAS.git"

ssh_options[:forward_agent] = true

set :deploy_to, "/var/www/vhosts/dev.ctsfla.com/atlas"

server "dev.ctsfla.com:22", :app, :web, :db, :primary => true
default_environment['PATH'] = "/var/www/vhosts/dev.ctsfla.com/atlas/shared/cakephp/cake/console:$PATH"
default_run_options[:pty] = true

set :user, 'devftp'

# Cake Settings
set :cake_branch, "1.3.7"

task :finalize_deploy, :roles => [:web] do
	#run "chmod 755 -R #{release_path}"
	run "mv #{release_path}/webroot/index_staging.php #{release_path}/webroot/index.php"
	run "mv #{release_path}/config/atlas.default.php #{release_path}/config/atlas.php"
	run "ln -s #{shared_path}/plugins #{current_release}/plugins"
	run "ln -s #{shared_path}/config/core.php #{current_release}/config/core.php" 
end

# remove the .git directory and .gitignore from the current release
desc "Remove git directories from release"
	task :remove_git_directories, :roles => [:web] do
	run "rm -rfd #{release_path}/.git"
	run "rm #{release_path}/.gitignore"
end

desc "Update database schema create tables"
	task :migrate_database_create, roles => [:web] do
	run "cd #{current_release} && cake schema create atlas < #{shared_path}/config/schema_create_prompt.txt"
end

desc "Update database schema update tables"
task :migrate_database_update, roles => [:web] do
	run "cd #{current_release} && yes y | cake schema update atlas"
end

after "deploy:update_code", :finalize_deploy
after "deploy:update_code", :remove_git_directories

capcake