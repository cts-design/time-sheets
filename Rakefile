desc 'Some quick tools to make working in our design repositories just a little bit easier'
namespace :design do
  task :checkout do
    branch = ENV['REPO']

    puts "= Cloning CTSATLAS/atlas-design into views/themed/#{branch}"
    sh "cd views/themed && git clone https://github.com/CTSATLAS/atlas-design.git #{branch}"

    puts "= Checking out the #{branch} branch"
    sh "cd views/themed/#{branch} && git checkout #{branch}"

    Rake::Task['symlink:stylesheets'].invoke branch
    Rake::Task['symlink:javascripts'].invoke branch
    Rake::Task['symlink:images'].invoke branch
    Rake::Task['design:set_theme_config'].invoke branch
  end

  desc 'Set the proper theme in config/atlas.php'
  task :set_theme_config, :branch do |t, args|
    branch = args.branch

    config = File.read 'config/atlas.php'
    File.open('config/atlas.php', 'w') do |file|
      file.puts config.gsub(/\$config\['Website'\]\['theme'\] = '[\w\S]*';/, "$config['Website']['theme'] = '#{branch}';")
    end
  end

  desc 'Remove the design repo and asset symlinks'
  task :cleanup do
    ignore = ['.', '..', '.DS_Store']
    branch = ''

    Dir.foreach 'views/themed' do |file|
      next if ignore.include? file
      branch = file
    end

    puts "= Removing the #{branch} theme folder"
    sh "cd views/themed && rm -rf #{branch}"

    puts "= Removing theme css assets from webroot"
    sh "rm -rf webroot/css/theme"

    puts "= Removing theme js assets from webroot"
    sh "rm -rf webroot/js/theme"

    puts "= Removing theme img assets from webroot"
    sh "rm -rf webroot/img/theme"

    config = File.read 'config/atlas.php'
    File.open('config/atlas.php', 'w') do |file|
      file.puts config.gsub(/\$config\['Website'\]\['theme'\] = '[\w\S]*';/, "$config['Website']['theme'] = '';")
    end
  end
end

namespace :symlink do
  task :stylesheets, :branch do |t, args|
    branch = args.branch
    source = File.absolute_path "views/themed/#{branch}/webroot/css"
    dest = File.absolute_path 'webroot/css'
    ignore = ['.', '..']

    puts "= Symlinking css assets"

    Dir.foreach source do |file|
      next if ignore.include? file

      absolute_source = "#{source}/#{file}"
      absolute_dest = "#{dest}/#{file}"

      sh "ln -fs #{absolute_source} #{absolute_dest}"
    end
  end

  task :javascripts, :branch do |t, args|
    branch = args.branch
    source = File.absolute_path "views/themed/#{branch}/webroot/js"
    dest = File.absolute_path 'webroot/js'
    ignore = ['.', '..']

    puts "= Symlinking js assets"

    Dir.foreach source do |file|
      next if ignore.include? file

      absolute_source = "#{source}/#{file}"
      absolute_dest = "#{dest}/#{file}"

      sh "ln -fs #{absolute_source} #{absolute_dest}"
    end
  end

  task :images, :branch do |t, args|
    branch = args.branch
    source = File.absolute_path "views/themed/#{branch}/webroot/img"
    dest = File.absolute_path 'webroot/img'
    ignore = ['.', '..']

    puts "= Symlinking image assets"

    Dir.foreach source do |file|
      next if ignore.include? file

      absolute_source = "#{source}/#{file}"
      absolute_dest = "#{dest}/#{file}"

      sh "ln -fs #{absolute_source} #{absolute_dest}"
    end
  end
end

namespace :database do 
  desc "Reformats the kiosk button table for it's newer version"
  task :kioskbutton do
    require 'rubygems'
    require 'mysql'
    user          = ENV['user']
    pass          = ENV['pass']
    database      = ENV['db']
    con           = Mysql.new 'localhost', user, pass, database
    kiosk_buttons = con.query("SELECT * FROM kiosk_buttons");

    con.autocommit(false);

    puts "---Preparing statement---"
    statement = con.prepare("UPDATE kiosk_buttons SET action_meta = ?, action = ? WHERE button_id = ?")
    kiosk_buttons.each_hash do |row|
      if (row['logout_message'] != nil && row['logout_message'] != '') && (row['action_meta'] == nil || row['action_meta'] == '')
        puts 'Setting button: ' + row['button_id'] + ' action_meta to: ' + row['logout_message'].to_s
        statement.execute row['logout_message'], 'logout', row['button_id']
      end
    end

    con.commit
  end
end
