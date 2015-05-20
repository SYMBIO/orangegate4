set :domain,		"agrofert.test.symbiodigital.com"
set :deploy_to,		"/var/www/test.symbiodigital.com/subdomains/agrofert"
set :apc_prefix,	"agrofert_test"
set :database_name,	"agrofert_test"
set :branch,		"master" # prod, master, ...
set :user,			"symbioftp" # symbioftp, symbiotestftp

role :web,			domain						# Your HTTP server, Apache/etc
role :app,			domain, :primary => true	# This may be the same as your `Web` server
