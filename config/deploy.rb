require 'rubygems'
require 'capcake'

set :application, 'atlas' # Your app's location (domain or sub-domain name)
set :repository, "git@git.assembla.com:CTSATLAS.git"
set :branch, 'staging'
set :deploy_via, :export

set :default_shell, '/bin/bash'

set :deploy_to, "/var/www/vhosts/development.ctsfla.com/atlas"

server "development.ctsfla.com", :app, :web, :db, :primary => true

set :user, 'dev4cts'

# Cake Settings
set :cake_branch, "master"

set :shared_children,       %w(config system tmp webroot/public/files webroot/public/img storage storage/thumbnails)

namespace :deploy do
	desc "Updates symlinks needed to make application work"
    task :symlink, :except => { :no_release => true } do
      on_rollback do
        if previous_release
          run "rm -f #{current_path}; ln -s #{previous_release} #{current_path}; true"
        else
          logger.important "no previous release to rollback to, rollback of symlink skipped"
        end
      end
      run "ln -s #{shared_path}/system #{latest_release}/webroot/system && ln -s #{shared_path}/tmp #{latest_release}/tmp";
      run "ln -s #{shared_path}/storage #{current_release}/storage"
      run "ln -s #{shared_path}/storage/thumbnails #{current_release}/storage/thumbnails"
      run "ln -s #{shared_path}/webroot/files/public #{current_release}/webroot/files/public"
      run "ln -s #{shared_path}/webroot/img/public #{current_release}/webroot/img/public"
      run "rm -f #{current_path} && ln -s #{latest_release} #{current_path}"    
    end	
end

desc "Update database schema create tables"
	task :migrate_database_create, roles => [:web] do
	run "cd #{current_release} && cake schema create atlas < #{shared_path}/config/schema_create_prompt.txt"
end

desc "Update database schema update tables"
task :migrate_database_update, roles => [:web] do
	run "cd #{current_release} && yes y | cake schema update atlas"
end  

task :finalize_deploy, :roles => [:web] do
	run "chmod 755 -R #{release_path}"	
	run "mv #{release_path}/webroot/index.staging.php #{release_path}/webroot/index.php"
	run "mv #{release_path}/config/atlas.default.php #{release_path}/config/atlas.php"
	run "mv #{release_path}/config/core.default.php #{release_path}/config/core.php"
end	

after "deploy:update_code", :finalize_deploy

capcake