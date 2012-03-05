require 'rubygems'
require 'capcake'

set :application, 'atlas' # app's location (domain or sub-domain name)
set :repository, "git@github.com:CTSATLAS/atlas.git"
set :branch, 'master'

set :deploy_via, :remote_cache

set :default_shell, '/bin/bash'

# branch to pull atlas design files from
set :design_branch, "master"

# --- Server Settings. 

# Staging and demo servers
namespace :cts do
  task :demo do
    set :deploy_to, "/var/www/vhosts/demo.atlasforworkforce.com/#{application}"
    set :server_name, 'cts demo'
    set :user, 'adidas_salad'
    set :branch, 'staging'
    server "demo.atlasforworkforce.com", :app, :web, :db, :primary => true
  end
  
  task :staging do  
    set :deploy_to, "/var/www/vhosts/development.ctsfla.com/#{application}"
    set :server_name, 'cts staging'
    set :user, 'dev4cts'
    set :branch, 'staging'
    set :design_branch, ENV['DESIGN'] if ENV.has_key?('DESIGN')
    server "development.ctsfla.com", :app, :web, :db, :primary => true
  end
  
  task :tradeshow do
    set :deploy_to, "/var/www/vhosts/www.ctsdemo.local/#{application}"
    set :server_name, 'cts tradeshow'
    set :user, 'demo_ftp'
    server "www.ctsdemo.local", :app, :web, :db, :primary => true    
  end
end

# Production servers
task :cccp do
  set :deploy_to, "/var/www/vhosts/vpk.childcarepinellas.org/#{application}"
  set :server_name, 'cccp production'
  set :user, 'vpk_ftp' 
  server "vpk.childcarepinellas.org", :app, :web, :db, :primary => true       
end

task :cc do
  set :deploy_to, "/var/www/vhosts/atlasv3.careercentral.jobs/#{application}"
  set :server_name, 'cc production'
  set :user, 'ccv3prod_ftp'
  server "192.168.200.46", :app, :web, :db, :primary => true
end

task :chipola do
  set :deploy_to, "/var/www/vhosts/atlas.onestopahead.com/#{application}"
  set :server_name, 'chipola production'
  set :user, 'ola_chip0'
  server "69.68.156.141", :app, :web, :db, :primary => true
end

task :clm do
  set :deploy_to, "/var/www/vhosts/atlas.clmworkforce.com/#{application}"
  set :server_name, 'clm production'
  set :user, 'clm_ftp' 
  server "atlas.clmworkforce.com", :app, :web, :db, :primary => true
end

task :elcm do
  set :deploy_to, "/var/www/vhosts/atlas.elc-marion.org/#{application}"
  set :server_name, 'elcm production'
  set :user, 'elcm_ftp' 
  server "atlas.elc-marion.org", :app, :web, :db, :primary => true       
end
  
task :tbwa do
  set :design_branch, "tbwa"
  set :server_name, 'tbwa production'
  set :deploy_to, "/var/www/vhosts/workforcetampa.com/#{application}"
  set :user, 'ftp_tbwa'
  server "workforcetampa.com", :app, :web, :db, :primary => true    
end

# --- Cake Settings
set :cake_branch, "1.3"

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
    run "ln -s #{shared_path}/storage #{latest_release}/storage"
    run "ln -s #{shared_path}/webroot/files/public #{latest_release}/webroot/files/public"
    run "ln -s #{shared_path}/webroot/img/public #{latest_release}/webroot/img/public"
    if (remote_file_exists?("#{shared_path}/webroot/img/default/default_header_logo.jpg"))
      run "ln -s #{shared_path}/webroot/img/default/default_header_logo.jpg #{latest_release}/webroot/img/default/default_header_logo.jpg"
    end 
    run "ln -s #{shared_path}/webroot/img/admin/admin_header_logo.jpg #{latest_release}/webroot/img/admin/admin_header_logo.jpg"
    run "ln -s #{shared_path}/webroot/img/kiosk/kiosk_header.jpg #{latest_release}/webroot/img/kiosk/kiosk_header.jpg"
    run "ln -s #{shared_path}/config/core.php #{latest_release}/config/core.php"
    run "ln -s #{shared_path}/config/atlas.php #{latest_release}/config/atlas.php"
    run "ln -s #{shared_path}/webroot/index.php #{latest_release}/webroot/index.php"
    run "ln -s #{shared_path}/webroot/test.php #{latest_release}/webroot/test.php"
    run "ln -s #{shared_path}/webroot/js/ckfinder/config.php #{latest_release}/webroot/js/ckfinder/config.php"
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

task :design do
  transaction do
    on_rollback { run "rm -rf #{release_path}; true" } 
    run "cd #{release_path} && git clone --depth 1 git://github.com/CTSATLAS/atlas-design.git design"
    set :git_flag_quiet, "-q "  
    stream "cd #{release_path}/design && git checkout #{git_flag_quiet}#{design_branch}"
    run "mv #{release_path}/design/img/default/ #{release_path}/webroot/img/"
    run "mv #{release_path}/design/js/default/ #{release_path}/webroot/js/"  
    run "mv #{release_path}/design/css/style.css #{release_path}/webroot/css/style.css"
    run "mv #{release_path}/design/views/layouts/default.ctp #{release_path}/views/layouts/default.ctp"
    run "mv #{release_path}/design/views/website_views/pages/home.ctp #{release_path}/views/website_views/pages/home.ctp"  
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

namespace :notify_campfire do
  deployer = ENV["USER"]

  desc 'Alert Campfire of a deploy'
  task :deploy_alert do
    branch_name = branch.split('/', 2).last   
    deployed = capture("cd #{previous_release} && git rev-parse HEAD")[0,7]
    deploying = capture("cd #{current_release} && git rev-parse HEAD")[0,7]
    compare_url = "https://github.com/CTSATLAS/atlas/compare/#{deployed}...#{deploying}"

    body =
      "#{deployer} deployed " +
      "#{branch_name} (#{deployed}..#{deploying}) to #{server_name} " +
      "(#{compare_url})"
      send_campfire_alert body
  end

  desc 'Alert Campfire of site disabled'
  task :disabled_alert do
    body = "#{deployer} put #{server_name} in maintenance mode."  
    send_campfire_alert body
  end

  desc 'Alert Campfire of site enabled'
  task :enabled_alert do
    body = "#{deployer} removed #{server_name} from maintenance mode."
    send_campfire_alert body
  end
end

def send_campfire_alert(body)
  run "cd #{current_release} && cake campfire '#{body}'" 
end

after "deploy:web:disable", "notify_campfire:disabled_alert"
after "deploy:web:enable", "notify_campfire:enabled_alert"
	
after "deploy:update_code", :design
after "deploy:symlink", :finalize_deploy
after :finalize_deploy, "notify_campfire:deploy_alert"

capcake