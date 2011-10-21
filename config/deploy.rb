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

set :shared_children,       %w(config system tmp tmp/fdf webroot/files/public webroot/img/public storage 
                               storage/thumbnails storage/program_forms storage/program_media)

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
      run "ln -s #{shared_path}/webroot/files/public #{current_release}/webroot/files/public"
      run "ln -s #{shared_path}/webroot/img/public #{current_release}/webroot/img/public"
      run "rm -f #{current_path} && ln -s #{latest_release} #{current_path}"    
    end	
end

namespace :cake do
  namespace :schema do
    desc "Update database schema create tables"
    	task :create, roles => [:web] do
    	run "cd #{current_release} && cake schema create atlas < #{current_release}/config/schema_create_prompt.txt"
    end
    
    desc "Update database schema update tables"
    task :update, roles => [:web] do
    	run "cd #{current_release} && yes y | cake schema update atlas"
    end
  end 
   
  desc "Update ACL Access Control Object Table" 
  task :aco_update, roles => [:web] do
    run "cd #{current_release} && cake acl_extras aco_update"
  end  
end

task :finalize_deploy, :roles => [:web] do
	run "chmod 755 -R #{release_path}"	
	run "mv #{release_path}/webroot/index.default.php #{release_path}/webroot/index.php"
	run "mv #{release_path}/webroot/test.default.php #{release_path}/webroot/test.php"
	run "mv #{release_path}/config/atlas.default.php #{release_path}/config/atlas.php"
	run "mv #{release_path}/config/core.default.php #{release_path}/config/core.php"
	run "mv #{release_path}/webroot/js/ckfinder/config.default.php #{release_path}/webroot/js/ckfinder/config.php"
end	

after "deploy:symlink", :finalize_deploy

after("cake:database:symlink", "cake:cache:clear")
after("cake:cache:clear", "cake:schema:create")
after("cake:schema:create", "cake:schema:update")
after("cake:schema:update", "cake:aco_update")
fter("cake:aco_update", "cake:cache:clear")

capcake