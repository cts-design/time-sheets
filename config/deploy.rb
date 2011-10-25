require 'rubygems'
require 'capcake'

set :application, 'atlas' # Your app's location (domain or sub-domain name)
set :repository, "git@git.assembla.com:CTSATLAS.git"

set :deploy_via, :export

set :default_shell, '/bin/bash'

# Server Settings. Be sure to wrap each region in it's own namespace. 

namespace :cts do
  task :demo do
    set :deploy_to, "/var/www/vhosts/demo.atlasforworkforce.com/#{application}"
    set :user, 'adidas_salad'
    set :branch, 'staging'
    server "demo.atlasforworkforce.com", :app, :web, :db, :primary => true
  end
  
  task :staging do  
    set :deploy_to, "/var/www/vhosts/development.ctsfla.com/#{application}"
    set :user, 'dev4cts'
    set :branch, 'staging'
    server "development.ctsfla.com", :app, :web, :db, :primary => true
  end
end

namespace :cccp do 
  task :staging do
    set :deploy_to, "/var/www/vhosts/ccc.atlasforworkforce.com/#{application}"
    set :user, 'b78ghfp6y'
    set :branch, 'staging'
    server "ccc.atlasforworkforce.com", :app, :web, :db, :primary => true    
  end
  task :production do
    set :deploy_to, "/var/www/vhosts/vpk.childcarepinellas.org/#{application}"
    set :user, 'vpk_ftp'
    set :branch, 'master'  
    server "vpk.childcarepinellas.org", :app, :web, :db, :primary => true       
  end  
end

namespace :cc do
  task :staging do
    set :deploy_to, "/var/www/vhosts/cc.atlasforworkforce.com/#{application}"
    set :user, 'ftp_cc_stage'
    set :branch, 'staging'
    server "cc.atlasforworkforce.com", :app, :web, :db, :primary => true
  end
  task :production do
    set :deploy_to, "/var/www/vhosts/atlasv3.careercentral.jobs/#{application}"
    set :user, 'ccv3prod_ftp'
    set :branch, 'master'
    server "192.168.200.46", :app, :web, :db, :primary => true
  end
end

namespace :chipola do
  task :staging do
    set :deploy_to, "/var/www/vhosts/chipola.atlasforworkforce.com/#{application}"
    set :user, 'ola_chip0'
    set :branch, 'staging'
    server "chipola.atlasforworkforce.com", :app, :web, :db, :primary => true
  end
end

namespace :clm do
  task :staging do
    set :deploy_to, "/var/www/vhosts/clmdev.ctsfla.com/#{application}"
    set :user, 'dev4clm'
    set :branch, 'staging'
    server "clmdev.ctsfla.com", :app, :web, :db, :primary => true     
  end
  task :production do
    set :deploy_to, "/var/www/vhosts/atlas.clmworkforce.com/#{application}"
    set :user, 'clm_ftp' 
    set :branch, 'master'
    server "atlas.clmworkforce.com", :app, :web, :db, :primary => true
  end
end

namespace :elcm do
  task :staging do 
    set :deploy_to, "/var/www/vhosts/elcm.atlasforworkforce.com/#{application}"
    set :user, 'ion_mar9'
    set :branch, 'staging'
    server "elcm.atlasforworkforce.com", :app, :web, :db, :primary => true    
  end
end

namespace :tbwa do
  task :staging do
    set :deploy_to, "/var/www/vhosts/tbwa.ctsfla.com/#{application}"
    set :user, 'tbwaftp'
    set :branch, 'staging'
    server "tbwa.ctsfla.com", :app, :web, :db, :primary => true
  end
  
  task :production do
    set :branch, 'master'
    set :deploy_to, "/var/www/vhosts/workforcetampa.com/atlas"
    set :user, 'ftp_tbwa'
    server "workforcetampa.com", :app, :web, :db, :primary => true    
  end
end

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
      run "ln -s #{shared_path}/webroot/img/default #{current_release}/webroot/img/default"
      run "ln -s #{shared_path}/webroot/js/default #{current_release}/webroot/js/default"
      run "ln -s #{shared_path}/webroot/img/admin/admin_header_logo.jpg #{current_release}/webroot/img/admin/admin_header_logo.jpg"
      run "ln -s #{shared_path}/webroot/img/kiosk/kiosk_header.jpg #{current_release}/webroot/img/kiosk/kiosk_header.jpg"
      run "ln -s #{shared_path}/config/core.php #{current_release}/config/core.php"
      run "ln -s #{shared_path}/config/atlas.php #{current_release}/config/atlas.php"
      run "ln -s #{shared_path}/webroot/index.php #{current_release}/webroot/index.php"
      run "ln -s #{shared_path}/webroot/test.php #{current_release}/webroot/test.php" 
      run "ln -s #{shared_path}/webroot/css/style.css #{current_release}/webroot/css/style.css"
      run "ln -s #{shared_path}/views/layouts/default.ctp #{current_release}/views/layouts/default.ctp"
      run "ln -s #{shared_path}/views/pages/home.ctp #{current_release}/views/pages/home.ctp"
      run "ln -s #{shared_path}/webroot/js/ckfinder/config.php #{current_release}/webroot/js/ckfinder/config.php"
      run "rm -f #{current_path} && ln -s #{latest_release} #{current_path}" 
      cake.database.symlink if (remote_file_exists?(database_path))   
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
	cake.cache.clear
	cake.schema.create
	cake.schema.update
	cake.aco_update
	cake.cache.clear
end	

after "deploy:symlink", :finalize_deploy

capcake