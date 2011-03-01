require 'rubygems'
require 'capcake'

set :application, 'atlas' # Your app's location (domain or sub-domain name)
set :repository, "git@git.assembla.com:CTSATLAS.git"
set :branch, 'tbwa_staging'
set :deploy_via, :export

ssh_options[:forward_agent] = true

set :deploy_to, "/var/www/vhosts/atlas.workforcetampa.com/atlas"

server "atlas.workforcetampa.com", :app, :web, :db, :primary => true
default_environment['PATH'] = "/var/www/vhosts/atlas.workforcetampa.com/atlas/shared/cakephp/cake/console:$PATH"
default_run_options[:pty] = true

set :user, 'wftftp'

# Cake Settings
set :cake_branch, "master"

task :finalize_deploy, :roles => [:web] do
	#run "chmod 755 -R #{release_path}"
	run "mv #{release_path}/webroot/index_staging.php #{release_path}/webroot/index.php"
	run "mv #{release_path}/config/atlas.default.php #{release_path}/config/atlas.php"
	run "ln -s #{shared_path}/plugins #{current_release}/plugins"
	run "ln -s #{shared_path}/config/core.php #{current_release}/config/core.php"
	run "ln -s #{shared_path}/storage #{current_release}/storage"
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

capcake