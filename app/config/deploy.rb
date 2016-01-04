set :stages,		%w(prod master)
set :default_stage,	"master"
set :stage_dir,		"app/config/deploy"
require 'capistrano/ext/multistage'

set :application,	"Agrofert"
set :repository,	"git://git.symbio.cz/agrofert/agrofert"
set :scm,			:git
set :use_sudo,		false
set :model_manager,	"doctrine"
set :keep_releases,	10

set :app_path,		"app"
set :web_path,		"web"

set :shared_files,		[app_path + "/config/parameters.yml"]
set :shared_children,	[app_path + "/logs", app_path + "/sessions", web_path + "/uploads", web_path + "/t", web_path + "/tr", "vendor", "node_modules"]
set :remove_children,	[web_path + "/static", web_path + "/sass", web_path + "/less", web_path + "/config.php"]
set :writable_dirs,		[app_path + "/logs", app_path + "/sessions", web_path + "/uploads", web_path + "/uploads/assets"]

set :use_composer, true
set :update_vendors, true
set :copy_vendors, false
set :use_set_permissions, true

#logger.level = Logger::MAX_LEVEL

after "deploy:restart", "deploy:cleanup"

#before "deploy", "symbio:upload_parameters"

after "deploy" do
	symbio.remove_unnecessary
	symbio.print_current_release
	symbio.apc.clear
end

# correction - capistrano do not know where composer is when current release was removed
before "deploy:rollback", "symbio:use_previous_release_composer"

after "deploy:rollback" do
	symbio.print_current_release
	symbio.apc.clear
end

after "deploy:rollback:cleanup", "symbio:apc:clear"

#before "symfony:composer:install" do
#	symbio.set_parameters
#end
#before "symfony:composer:update" do
#	symbio.set_parameters
#end

namespace :deploy do
	task :set_permissions, :except => { :no_release => true } do
		logger.debug "shared_path: #{shared_path}, releases_path: #{releases_path}, release_name: #{release_name}"
		release_path = File.join(releases_path, release_name)
		dirs = writable_dirs.map { |d| File.join((shared_children.include? d) ? shared_path : release_path, d) }
		logger.debug "Set permissions to #{dirs.join(' ')}"
		run "mkdir -p #{dirs.join(' ')}"
		run "chmod 777 #{dirs.join(' ')}"
	end
end

namespace :symbio do
	task :upload_parameters do
		run "mkdir -p #{shared_path}/#{app_path}/config"
		logger.debug "Upload parameters ftom #{app_path}/config/parameters.yml to #{shared_path}/#{app_path}/config/parameters.yml"
		upload "#{app_path}/config/parameters.yml", "#{shared_path}/#{app_path}/config/parameters.yml"
	end

	task :set_parameters do
		logger.debug "Generate script_version"0.
		run "
			awk '/script_version/{print var}1' var=\"    script_version: `echo #{release_name} | md5sum | cut -f1 -d' '`\" #{shared_path}/#{app_path}/config/parameters.yml > #{shared_path}/#{app_path}/config/.parameters.yml;
			mv -f #{shared_path}/#{app_path}/config/.parameters.yml #{shared_path}/#{app_path}/config/parameters.yml;
		"
		logger.debug "Write parameter database_name"
		run "
			awk '/database_name/{print var}1' var=\"    database_name: `echo #{database_name}`\" #{shared_path}/#{app_path}/config/parameters.yml > #{shared_path}/#{app_path}/config/.parameters.yml;
			mv -f #{shared_path}/#{app_path}/config/.parameters.yml #{shared_path}/#{app_path}/config/parameters.yml;
		"
	end

	namespace :apc do
		# page /_service/apc-clear.php is required
		task :clear do
			# test if some release exists - current_release still contains previous release path even if next release is on the way
			if current_release then
				capifony_pretty_print "--> Clear APC cache"
				hash = (0...32).map { ('a'..'z').to_a[rand(26)] }.join
				run "curl -s -G http://#{domain}/_service/apc-clear.php -d prefix=#{apc_prefix} -d user=1 -d hash=#{hash}" do |channel, stream, data|
					logger.debug "#{data}"
				end
				capifony_puts_ok
			end
		end
	end

	task :remove_unnecessary do
		logger.debug "releases_path: #{releases_path}, release_name: #{release_name}"
		release_path = File.join(releases_path, release_name)
		children = remove_children.map { |d| File.join(release_path, d) }
		logger.debug "Removing #{children.join(' ')}"
		run "rm -rf #{children.join(' ')}"
	end

	task :print_current_release do
		capifony_pretty_print "--> Current release #{release_name}"
		capifony_puts_ok
	end

	task :use_previous_release_composer do
		logger.debug "Set composer path #{previous_release}/composer.phar"
		set :composer_bin, previous_release + "/composer.phar"
	end
end