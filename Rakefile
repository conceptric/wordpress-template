root                      = File.dirname( __FILE__ ) 
wp_version                = "3.9.2"                 
app_path                  = "#{root}/app"
build_path                = "#{root}/public"
development_shared_path   = "#{root}/shared"
vendor_path               = "#{root}/vendor"
wp_path                   = "#{vendor_path}/wordpress"
vendor_plugins_path       = "#{vendor_path}/plugins"
wp_unwanted_files         = %w{readme.html license.txt wp-config-sample.php}

desc "Run this command to setup the project structure"
task :setup do
  %x{mkdir #{root}/backup}
  %x{mkdir #{build_path}}
  %x{mkdir -p #{app_path}/assets #{app_path}/themes}
  %x{mkdir -p #{development_shared_path}/assets} 
  %x{mkdir -p #{development_shared_path}/development}
  %x{mkdir -p #{development_shared_path}/uploads}
  puts "Created a development directories"

  %x{touch #{development_shared_path}/assets/sitemap.xml}
  %x{touch #{development_shared_path}/assets/sitemap.xml.gz}
  %x{touch #{development_shared_path}/assets/wp-config.php}
  puts "Created standard development files"

  File.open("#{development_shared_path}/assets/wp-config.php", 'w') do |f|
    f.puts "<?php"
    f.puts "define('DB_NAME', 'database_name');"
    f.puts "define('DB_USER', 'database_user');"
    f.puts "define('DB_PASSWORD', 'password');"
    f.puts "define('DB_HOST', 'localhost');"
    f.puts "define('DB_CHARSET', 'utf8');"
    f.puts "define('DB_COLLATE', '');"
    f.puts "$table_prefix  = 'wp_';"
    f.puts "define ('WPLANG', '');"
    f.puts "define('ABSPATH', dirname(__FILE__).'/');"
    f.puts "require_once(ABSPATH.'wp-settings.php');"
    f.puts "?>"
  end
  puts "Created default development wp-config"
end

desc "Upgrade the WordPress in vendors to the specified version"
task :upgrade_wordpress do           
  puts "Removing existing version"
  %x{rm -Rf #{wp_path}/*} 
  puts "Exporting from the Subversion Repo"
  %x{svn export --force \
    http://core.svn.wordpress.org/tags/#{wp_version}/ #{wp_path}/}
  puts "Export done"
end

desc "Builds the development application in the public directory"
task :build_app => [:build_wordpress, :database, :build_uploads, :add_trimmings] do
  puts "Done building the application"
end        
            
desc "Remove clutter from the standard WordPress installation"
task :build_wordpress do
  puts "Removing existing installation"
  sh "rm -Rf #{build_path}/*"
  puts "Copying the current WordPress version"
  sh "cp -Rf #{wp_path}/* #{build_path}/"
  puts "Removing unnecessary files from the default installation"
  wp_unwanted_files.each do |afile|
    sh "rm -f #{build_path}/#{afile}"
  end  
  puts "Files removed"
end

desc "Adds plugins, themes and assets to the basic WordPress install"
task :add_trimmings => [:build_assets, :build_plugins, :build_themes] do
  puts "The application extras have been added"
end

desc "Copying all the vendor plugins"
task :build_plugins do
  puts "Copying the vendor plugins"
  Dir.glob("#{vendor_plugins_path}/*").each do |plugin|
    sh "cp -Rf #{plugin} \
     #{build_path}/wp-content/plugins/#{plugin.gsub("#{vendor_plugins_path}/", "")}"
  end 
  puts "Done"
end

desc "Linking all the application themes"
task :build_themes do
  puts "Linking the application themes"
  Dir.glob("#{app_path}/themes/*").each do |theme|
    sh "ln -nfs #{theme} \
     #{build_path}/wp-content/themes/#{theme.gsub("#{app_path}/themes/", "")}"
  end 
  puts "Done"
end

desc "Copying application assets"
task :build_app_assets do
  puts "Copying application assets"
  Dir.glob("#{app_path}/assets/*").each do |asset|
    sh "cp -R #{asset} \
      #{build_path}/#{asset.gsub("#{app_path}/assets/", "")}"
  end 
  puts "Done"  
end

# Development Only

desc "Build the assets: includes local developer assets"
task :build_assets => [:build_app_assets, :build_developer_assets] do
  puts "Assets added to the application"
end

desc "Build the uploads: includes local developer uploads"
task :build_uploads => [:build_developer_uploads] do
  puts "Upload directory configured"
end 

desc "Copy the local developer database configuration file"
task :database do
  puts "Copying developer database configuration file"
  %x{cp -f #{development_shared_path}/assets/wp-config.php #{build_path}/}
  puts "Done"
end                                                              

desc "Linking developer shared uploads"
task :build_developer_uploads do
  puts "Link the shared uploads"
  %x{ln -nfs #{development_shared_path}/uploads \
    #{build_path}/wp-content/uploads}
  %x{ln -nfs #{development_shared_path}/uploads \
    #{build_path}/uploads} 
  puts "Done"
end 

desc "Linking developer shared assets"
task :build_developer_assets do
  puts "Linking local sitemaps"
  Dir.glob("#{development_shared_path}/assets/sitemap*").each do |asset|
    sh "ln -s #{asset} \
      #{build_path}/#{asset.gsub("#{development_shared_path}/assets/", "")}"
  end 
  puts "Done"  
end
