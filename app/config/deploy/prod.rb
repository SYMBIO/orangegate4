set :domain,		"www.agrofert.cz"
set :deploy_to,		"/var/www/agrofert.cz"
set :apc_prefix,	"agrofert_prod"
set :database_name,	"agrofert_agrofert"
set :branch,		"prod" # prod, master, ...
set :user,			"symbioftp" # symbioftp, symbiotestftp

role :web,			domain						# Your HTTP server, Apache/etc
role :app,			domain, :primary => true	# This may be the same as your `Web` server
