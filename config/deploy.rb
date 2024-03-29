require File.join(File.dirname(__FILE__), '../libs/capcake/capcake')

set :application, 'atlas' # app's location (domain or sub-domain name)
set :repository, "git@github.com:CTSATLAS/atlas.git"
set :branch, 'master'

set :user, 'deploy'
set :deploy_to, "/var/www/vhosts/deploy/#{application}"
set :deploy_via, :remote_cache

set :default_shell, '/bin/bash'

# branch to pull atlas design files from
set :design_branch, "master"

# design branch architecture
# This is only necessary until all the new designs are deployed and
# all the old branches are changed over to the CakePHP "theme architecture"
set :design_architecture, 'old'

# plugins, override in region namespace if region has plugins
set :app_plugins, []

# number of releases to keep after running cap deploy:cleanup
set :keep_releases, 10

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

  task :internal do
    set :server_name, 'cts internal'
    set :user, 'deploy'
    set :branch, 'master'
    server "atlas.ctsfla.com", :app, :web, :db, :primary => true
  end

  task :staging do
    set :deploy_to, "/var/www/vhosts/staging.atlasforworkforce.com/#{application}"
    set :server_name, 'atlas staging'
    set :user, 'atlas_staging'
    set :keep_releases, 2
    set :branch, 'staging'
    set :design_branch, 'tbwa-new'
    set :design_architecture, 'new'
    server "staging.atlasforworkforce.com", :app, :web, :db, :primary => true
  end

  task :design do
    set :deploy_to, "/var/www/vhosts/design.atlasforworkforce.com/#{application}"
    set :server_name, 'atlas design'
    set :user, 'design_deploy'
    set :keep_releases, 2
    set :branch, 'staging'
    set :design_branch, "chipola"
    server "192.168.200.74", :app, :web, :db, :primary => true
  end

  task :tradeshow do
    set :deploy_to, "/var/www/vhosts/www.ctsdemo.local/#{application}"
    set :server_name, 'cts tradeshow'
    set :user, 'cts_demo'
    server "www.ctsdemo.local", :app, :web, :db, :primary => true
  end
end


# Temp servers
task :design2014_chipola do
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :server_name, 'CHIPOLA DESIGN'
  set :user, 'deploy'
  set :design_branch, 'chipola-new'
  set :design_architecture, 'new'
  server "192.168.200.191", :app, :web, :db, :primary => true
end

######################
##### ELC's
task :elc_duval do
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :server_name, 'atlas.elcofduval.org (ELC-Duval)'
  set :user, 'deploy'
  server "atlas.elcofduval.org", :app, :web, :db, :primary => true
end

task :elc_heartland do
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :server_name, 'vpkelcfh.org (ELC-Heartland)'
  set :user, 'deploy'
  server "75.148.105.140", :app, :web, :db, :primary => true
end

task :elc_hillsborough do
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :server_name, 'vpk.elchc.org (ELC-Hillsborough)'
  set :user, 'deploy'
  server "192.168.200.193", :app, :web, :db, :primary => true
end

task :elc_lake do
  set :server_name, 'atlas.elclc.org (ELC-Lake)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "192.168.200.195", :app, :web, :db, :primary => true
end

task :elc_manatee do
  set :server_name, 'vpk.elc-manatee.org (ELC-Manatee)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "192.168.200.116", :app, :web, :db, :primary => true
end

task :elc_marion do
  set :deploy_to, "/var/www/vhosts/atlas.elc-marion.org/#{application}"
  set :server_name, 'atlas.elc-marion.org (ELC-Marion)'
  set :user, 'elcm_ftp'
  server "atlas.elc-marion.org", :app, :web, :db, :primary => true
end

task :elc_mdm do
  set :server_name, 'atlas.elcmdm.org (ELC-Miami-Dade-Monroe)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "atlas.elcmdm.org", :app, :web, :db, :primary => true
end

task :elc_pasco_hernando do
  set :server_name, 'elc-pasco-hernando (ELC-Pasco-Hernando)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "atlas.phelc.org", :app, :web, :db, :primary => true
end

# This one is really old, don't deploy to it unless to
task :elc_pinellas do
  set :deploy_to, "/var/www/vhosts/vpk.childcarepinellas.org/#{application}"
  set :server_name, 'vpk.elcpinellas.net (ELC-Pinellas)'
  set :user, 'vpk_ftp'
  server "vpk.elcpinellas.net", :app, :web, :db, :primary => true
end

task :elc_polk do
  set :server_name, 'vpkelcpolk.org (ELC-Polk)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "192.168.200.57", :app, :web, :db, :primary => true
end

task :elc_sarasota do
  set :server_name, 'vpk.earlylearningcoalitionsarasota.org (ELC-Sarasota)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "192.168.200.194", :app, :web, :db, :primary => true
end

task :elc_seminole do
  set :server_name, 'vpk.SeminoleEarlyLearning.org (ELC-Seminole)'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "192.168.200.192", :app, :web, :db, :primary => true
end

# Production servers
# 5pm deploys

task :cs_south_florida do
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :server_name, 'careersource south florida'
  set :user, 'deploy'
  server "68.17.60.50", :app, :web, :db, :primary => true
end

task :cs_pasco_hernando do
  set :deploy_to, "/var/www/vhosts/atlasv3.careercentral.jobs/#{application}"
  set :server_name, 'careersource pasco hernando'
  set :user, 'ccv3prod_ftp'
  set :design_branch, "ccentral-new"
  set :design_architecture, 'new'
  server "192.168.200.46", :app, :web, :db, :primary => true
end

task :cs_clm do
  set :deploy_to, "/var/www/vhosts/atlas.clmworkforce.com/#{application}"
  set :server_name, 'careersource clm'
  set :user, 'clm_ftp'
  server "atlas.clmworkforce.com", :app, :web, :db, :primary => true
end

task :cs_capital_region do
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :server_name, 'careersource capital region'
  set :user, 'deploy'
  server "199.44.92.153", :app, :web, :db, :primary => true
end

task :rescare do
  set :server_name, 'rescare'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  set :port, 23
  server "montgomery.rescare.com", :app, :web, :db, :primary => true
end
task :cs_suncoast do # deploy@atlas.suncoastworkforce.org
  set :server_name, 'suncoast production'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "atlas.suncoastworkforce.org", :app, :web, :db, :primary => true
end

task :cs_tampa_bay do
  set :design_branch, "tbwa-new"
  set :design_architecture, 'new'  
  set :server_name, 'careersource tampa bay'
  set :deploy_to, "/var/www/vhosts/workforcetampa.com/#{application}"
  set :user, 'ftp_tbwa'
  server "workforcetampa.com", :app, :web, :db, :primary => true
  set :app_plugins, ['job_forms']
end

task :united_way do
  set :design_branch, 'united-way'
  set :design_architecture, 'new'
  set :server_name, 'job smart tampa bay production'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  server "jobsmarttampabay.com", :app, :web, :db, :primary => true
end

task :cs_pinellas do
  set :server_name, 'careersource pinellas'
  set :design_branch, 'worknet-pinellas'
  set :design_architecture, 'new'
  server "10.66.49.13", :app, :web, :db, :primary => true
end

task :temp_worknetpinellas do
  set :server_name, '*TEMP* worknetpinellas'
  server "192.168.200.97", :app, :web, :db, :primary => true
end

task :wrec do
  set :server_name, 'wrec v2'
  set :deploy_to, "/var/www/vhosts/wrec-v2.ctsfla.com/#{application}"
  set :user, 'wrec_new'
  server "192.168.200.108", :app, :web, :db, :primary => true
end

# 6pm deploys
task :cs_chipola do
  set :design_branch, "chipola-new"
  set :design_architecture, 'new'
  set :deploy_to, "/var/www/vhosts/atlas.onestopahead.com/#{application}"
  set :server_name, 'careersource chipola'
  set :user, 'ola_chip0'
  server "23.25.183.162", :app, :web, :db, :primary => true
end

task :cs_okaloosa_walton do
  set :server_name, 'careersource okaloosa walton'
  set :deploy_to, "/var/www/vhosts/deploy/#{application}"
  set :user, 'deploy'
  set :design_branch, 'okaton'
  set :design_architecture, 'new'
  server '68.225.125.205', :app, :web, :db, :primary => true
end

# --- Cake Settings
set :cake_branch, "1.3"

set :shared_children,       %w(config backups plugins system tmp tmp/fdf webroot/files/public
                               webroot/img/public storage storage/thumbnails storage/program_forms
                               storage/program_media storage/ecourse_forms storage/ecourse_media)

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
    run "rm -f #{current_path} && ln -s #{latest_release} #{current_path}"
    cake.database.symlink if (remote_file_exists?(database_path))

    run "ln -s #{shared_path}/storage #{latest_release}/storage"
    run "ln -s #{shared_path}/webroot/files/public #{latest_release}/webroot/files/public"
    run "ln -s #{shared_path}/webroot/img/public #{latest_release}/webroot/img/public"
    # Commented out 1/28/14 by Bill - was causing deploy to crash if a theme was defined:
    #if (remote_file_exists?("#{shared_path}/webroot/img/default/default_header_logo.jpg"))
    run "ln -s #{shared_path}/webroot/img/default/default_header_logo.jpg #{latest_release}/webroot/img/default/default_header_logo.jpg"
    #end
    run "ln -s #{shared_path}/webroot/img/admin/admin_header_logo.jpg #{latest_release}/webroot/img/admin/admin_header_logo.jpg"
    run "ln -s #{shared_path}/webroot/img/kiosk/kiosk_header.jpg #{latest_release}/webroot/img/kiosk/kiosk_header.jpg"
    run "ln -s #{shared_path}/config/core.php #{latest_release}/config/core.php"
    run "ln -s #{shared_path}/config/atlas.php #{latest_release}/config/atlas.php"
    run "ln -s #{shared_path}/webroot/index.php #{latest_release}/webroot/index.php"
    run "ln -s #{shared_path}/webroot/test.php #{latest_release}/webroot/test.php"
    run "ln -s #{shared_path}/webroot/js/ckfinder/config.php #{latest_release}/webroot/js/ckfinder/config.php"
    #run "ln -s #{shared_path}/storage/signatures #{latest_release}/webroot/signatures"
    deploy.plugins.symlink
  end

  namespace :plugins do
    desc "Symlinks the configured plugins for the appliction into plugins, from the shared dirs."
    task :symlink, :except => { :no_release => true } do
      app_plugins.each { |plugin|
        run "ln -s #{shared_path}/plugins/#{plugin} #{latest_release}/plugins/#{plugin}"
      }
    end
  end

  task :finalize_update, :except => { :no_release => true } do
    cake.cache.clear
    cake.schema.create
    cake.schema.update
    cake.aco_update
    cake.cache.clear
  end
end

namespace :cake do
  namespace :schema do
    desc "Update database schema create tables"
    task :create, roles => [:web] do
      run "cd #{latest_release}&& cake schema create atlas < #{latest_release}/config/schema_create_prompt.txt"
    end

    desc "Update database schema update tables"
    task :update, roles => [:web] do
      run "cd #{latest_release} && yes y | cake schema update atlas"
    end
  end

  desc "Update ACL Access Control Object Table"
  task :aco_update, roles => [:web] do
    run "cd #{latest_release} && cake acl_extras aco_update"
  end
end

task :design do
  if design_architecture == 'new'
    transaction do
      on_rollback { run "rm -rf #{release_path}; true" }
      run "if [ ! -d #{release_path}/views/themed/ ]; then mkdir #{release_path}/views/themed; fi"
      run "cd #{release_path}/views/themed && git clone --depth 1 git://github.com/CTSATLAS/atlas-design.git #{design_branch}"
      set :git_flag_quiet, "-q "
      stream "cd #{release_path}/views/themed/#{design_branch} && git checkout #{git_flag_quiet}#{design_branch}"
      run "cd #{release_path}/webroot/css && ln -s #{release_path}/views/themed/#{design_branch}/webroot/css/theme theme"
      run "cd #{release_path}/webroot/js && ln -s #{release_path}/views/themed/#{design_branch}/webroot/js/theme theme"
      run "cd #{release_path}/webroot/img && ln -s #{release_path}/views/themed/#{design_branch}/webroot/img/theme theme"
    end
  else design_architecture == 'old'
    transaction do
      on_rollback { run "rm -rf #{release_path}; true" }
      run "cd #{release_path} && git clone --depth 1 git://github.com/CTSATLAS/atlas-design.git design"
      set :git_flag_quiet, "-q "
      stream "cd #{release_path}/design && git checkout #{git_flag_quiet}#{design_branch}"
      run "mv #{release_path}/design/js/default/ #{release_path}/webroot/js/"
      run "mv #{release_path}/design/css/style.css #{release_path}/webroot/css/"
      run "mv #{release_path}/design/views/layouts/default.ctp #{release_path}/views/layouts/default.ctp"
      run "mv #{release_path}/design/views/website_views/pages/home.ctp #{release_path}/views/website_views/pages/home.ctp"

      run "if [ -d #{release_path}/webroot/img/default ]; then rm -rf #{release_path}/webroot/img/default; fi"
      run "mv #{release_path}/design/img/default/ #{release_path}/webroot/img/"
    end
  end
end


namespace :notify_campfire do
  deployer = ENV["USER"]

  desc 'Alert Campfire of a deploy'
  task :deploy_alert do
    branch_name = branch.split('/', 2).last
    if current_release
      deployed = capture("cd #{current_release} && git rev-parse HEAD")[0,7]
    else
      deployed = 'no current release'
    end
    deploying = capture("cd #{latest_release} && git rev-parse HEAD")[0,7]
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

  desc 'Alert Campfire of database backup'
  task :mysql_backup_alert do
    body = "#{server_name} database backup complete"
    send_campfire_alert body
  end

end

def send_campfire_alert(body)
  if current_release
    run "cd #{current_release} && cake campfire '#{body}'"
  end
end

namespace :mysql do
  desc "performs a backup (using mysqldump) in app shared dir"
  task :backup do
    filename = "#{application}.db_backup.#{Time.now.to_f}.sql.bz2"
    filepath = "#{shared_path}/backups/#{filename}"
    text = capture "cat #{shared_path}/config/database.yml"
    yaml = YAML::load(text)

    on_rollback { run "rm #{filepath}" }
    run "mysqldump -u #{yaml['production']['username']} -p #{yaml['production']['database']} | bzip2 -c > #{filepath}" do |ch, stream, out|
      ch.send_data "#{yaml['production']['password']}\n" if out =~ /^Enter password:/
    end

  end

end

before :deploy, 'mysql:backup'

after "mysql:backup", "notify_campfire:mysql_backup_alert"
after "deploy:web:disable", "notify_campfire:disabled_alert"
after "deploy:web:enable", "notify_campfire:enabled_alert"
after "deploy:update_code", :design
after "deploy:plugins:symlink", "deploy:finalize_update"
after "deploy:finalize_update", "deploy:cleanup"
after "deploy:cleanup", "notify_campfire:deploy_alert"

capcake
