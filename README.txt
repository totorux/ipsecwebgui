Summary:

This tools is to managed IPSec VPN, with two level users (Admin and user).

Intallation instructions:

The tools need apache, mysql, fping, sudo, openswan (if not installed)

# aptitude install sudo apache2 php5 mysql-server openswan

Run "./scriptvpn.sh install" to install the sofware.

Then use visudo to allow apache to manage ipsec:

# Cmnd alias specification
Cmnd_Alias      IPSEC = /usr/sbin/ipsec
# User privilege specification
www-data ALL=NOPASSWD:IPSEC

At start, the tools is in demo mode, so it will not be able to manage VPN (restart, add ...) it can only load actual configuration.

To enable production mode edit scriptvpn.sh and change:

scriptstat="demo"
to
scriptstat="prod"

Default admin user:
admin // admin123

Command line site reload:
./scriptvpn.sh vpnsync sitename

Important folder:

/etc/ipsec.d/sites is the files where all differents VPN configuration will be.
/etc/ipsec.secrets is the file where are preshared key
