Usual commande:

Intallation insctuction:

need apache, mysql fping sudo: aptitude install sudo apache2 php5 mysql-server openswan
Run "./scriptvpn.sh install" to install the sofware.
Then use visudo to allow apache to manage ipsec:

# Cmnd alias specification
Cmnd_Alias      IPSEC = /usr/sbin/ipsec
# User privilege specification
www-data ALL=NOPASSWD:IPSEC

Then go on the 

4) edit scriptvpn.sh and change:

scriptstat="demo"
to
scriptstat="prod"

Default admin user:
admin // admin123

Commande line site reload:
./scriptvpn.sh vpnsync sitename
