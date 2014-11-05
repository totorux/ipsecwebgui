#!/bin/bash

##############################################################################
#                                                                            #
# Description : vpngui: Ipsec Web GUI for manage ipsec VPN                   #
# OS          : Linux                                                        #
# Licence     : GPLv3                                                        #
# Version     : 0.3.3                                                        #
# Author      : Schneider Benoit <ton.ami.totoro CHEZ gmail.com>             #
# Web site    : https://www.totorux.info                                     #
#                                                                            #
#                                                                            #
#                                                                            #
#    vpngui is free software: you can redistribute it and/or modify          #
#    it under the terms of the GNU General Public License as published by    #
#    the Free Software Foundation, either version 3 of the License, or       #
#    (at your option) any later version.                                     #
#                                                                            #
#    vpngui is distributed in the hope that it will be useful,               #
#    but WITHOUT ANY WARRANTY; without even the implied warranty of          #
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           #
#    GNU General Public License for more details.                            #
#                                                                            #
#    You should have received a copy of the GNU General Public License       #
#    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.         #
#                                                                            #
##############################################################################
#                                                                            #
#    Required :                                                              #
#    Openswan, Apache, mysql, php                                            #
#                                                                            #
##############################################################################
#                                                                            #
#    Tested on: Debian Squeeze - Openswan 2.6.28                             #
#                                                                            #
##############################################################################

#Variables

# Script status: demo / prod

	scriptstat="demo"

# Path

# Log file

	logfile="/var/log/vpngui.log"

# Script path

	scriptpath="/var/www/vpngui"

# Site conf file directory

	siteconfpath="/etc/ipsec.d/sites/"

# Secrets file path

	secretconffile="/etc/ipsec.secrets"

# Binary and commande

	ipsecsetup="sudo /usr/sbin/ipsec _realsetup"

	ipsecauto="sudo /usr/sbin/ipsec auto"

	ipsecbin="/usr/sbin/ipsec"

# Temp file

	reloadedvpn="/tmp/reloadedvpn.tmp"

# Number of backup you want to keep

	saveqty=10

# Files list you want to backup seperate by a space

	filetobackup="$secretconffile"

# Folder list you want to backup seperate by a space

	foldertobackup="/etc/ipsec.d"

# Ping configuration

	pingtype="local" # "dist" for distant, "local" for local ping

	pinguser="adminuser"
	pingsrv="dist-server"


# Mysql database information backup

#Mysql user

	mysqldatabaseuser=`cat $scriptpath/connect.php | grep mysql_connect | awk -F "'" '{print $4}'`
# Mysql user's password

	mysqldatabasepwd=`cat $scriptpath/connect.php | grep mysql_connect | awk -F "'" '{print $6}'`

# Mysql base to backup

	mysqldatabase=`cat $scriptpath/connect.php | grep mysql_select_db | awk -F "'" '{print $2}'`

# Mysql server

	mysqldatabasehost=`cat $scriptpath/connect.php | grep mysql_connect | awk -F "'" '{print $2}'`

# Temp folder

	tmpfolder="/tmp/backup"

# Script init

	tmpfoldertest=0

	backupfilename="save-`date +"%y-%m-%d"`.tar.gz"

	defaultdateform="`date +"%y-%m-%d at %X"`"

# Connexion Mysql

	 mysql_connect="/usr/bin/mysql -A -u$mysqldatabaseuser -p$mysqldatabasepwd -D$mysqldatabase"

if [ ! -f $reloadedvpn ]
then
	touch $reloadedvpn
fi

# Apply good right on log file
if [ `whoami` != "www-data" ]
then
	for file in $reloadedvpn $logfile
	do
		if [ ! -f $logfile ]
		then
		    touch $file
		fi
		chmod 774 $file
		chown :www-data $file
done
fi
# Active VPN list

cd $scriptpath

## Fonction

function chkvpn() 
{
cpt=0   
limx=$(($limx - 1))
for cpt in `seq $limx`
do
	if [ ${ARRAY[$cpt:1]} == $vpn ];then
                echo "established"
	fi
done
} 


function chkvpnmysql() 
{
	echo 'SELECT `siteconf` FROM site_conf WHERE siteconf = "'${1}'"' | $mysql_connect | sed '/siteconf/d' | wc -l
}


case ${1} in

#### Template management ####

templateadd)

	templatekey=${2}
	left=${3}
	leftid=${4}
	leftsubnet=${5}
	secretkey=${6}
	auth=${7}
	ike=${8}
	authby=${9}
	auto=${10}
	compress=${11}
	pfs=${12}
	vpntype=${13}
	keylife=${14}
	rekey=${15}
	esp=${16}

	cp $scriptpath/template/vpntemplate-ini.conf $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/AUTH/$auth/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/IKE/$ike/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/ABY/$authby/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/AUTO/$auto/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/COMPRESS/$compress/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/PFS/$pfs/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/TYPE/$vpntype/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/KEYLIFE/$keylife/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/REKEY/$rekey/g" $scriptpath/template/vpntemplate-$templatekey.conf
	sed -i "s/ESP/$esp/g" $scriptpath/template/vpntemplate-$templatekey.conf
;;

templateupdate)

	templatekey=${2}
	left=${3}
	leftid=${4}
	leftsubnet=${5}
	secretkey=${6}
	auth=${7}
	ike=${8}
	authby=${9}
	auto=${10}
	compress=${11}
	pfs=${12}
	vpntype=${13}
	keylife=${14}
	rekey=${15}
	esp=${16}
	rm $scriptpath/template/vpntemplate-$templatekey.conf
	cp $scriptpath/template/vpntemplate-ini.conf $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/AUTH/$auth/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/IKE/$ike/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/ABY/$authby/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/AUTO/$auto/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/COMPRESS/$compress/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/PFS/$pfs/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/TYPE/$vpntype/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/KEYLIFE/$keylife/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/REKEY/$rekey/g" $scriptpath/template/vpntemplate-$templatekey.conf
        sed -i "s/ESP/$esp/g" $scriptpath/template/vpntemplate-$templatekey.conf
;;

templatedel)

	templatekey=${2}
	rm $scriptpath/template/vpntemplate-$templatekey.conf
;;

# Backup check

chekbackup)
	file=${2}
	cd $scriptpath/upload/
	tar -xzvf $file
	folderlst=`ls -d */ | sed "s/\///g"`
        ndfolder=0
	for folder in $folderlst
	do
	case $folder in
		bases)
			((ndfolder++))
		;;
		files)
			((ndfolder++))
		;;
		folder)
			((ndfolder++))
		;;
		*)
			ndfolder = $ndfolder
		;;
	esac
	done
        if [ $ndfolder == 3 ]
        then
                echo "ok"
		rm $scriptpath/upload/$file
        else
                echo "nok"
        fi
;;

#### Site port congfiguration ####

siteporttest)

	site=${2}
	usrlvl=${3}

        for var in porttest $site $usrlvl
        do
                echo $var >> working/$site.task
        done

;;

#### VPN management ####

# Reload and synchronize VPN file configruation to mysql configuration

vpnsync)

	if [ $(ls $siteconfpath | grep ${2} | wc -l) -gt 0 ]
	then
		site=${2}
	else
		vpn=${2}
		site=`echo $vpn | sed "s/.$//"`
	fi

# We stop Site VPN

        for vpn in `cat $siteconfpath$site.conf | grep conn | sed 's/conn //g'`
        do
                /bin/bash $scriptpath/scriptvpn.sh stopvpn $vpn
        done
        nblock=1
        for vpn in `cat $siteconfpath$site.conf | grep conn | sed 's/conn //g'`
        do
                sitefile=$site
                leftfile=`cat $siteconfpath$sitefile.conf  | grep "left="  | sed "s/left=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == $nblock {print;}"`
                leftidfile=`cat $siteconfpath$sitefile.conf  | grep "leftid="  | sed "s/leftid=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
                leftsubnetfile=`cat $siteconfpath$sitefile.conf  | grep "leftsubnet="  | sed "s/leftsubnet=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
                rightfile=`cat $siteconfpath$sitefile.conf  | grep "right="  | sed "s/right=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == $nblock {print;}"`
                rightidfile=`cat $siteconfpath$sitefile.conf  | grep "rightid="  | sed "s/rightid=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
                keyfile=`cat $secretconffile | grep " $rightidfile " | awk '{print $5}' | sed 's/"//g'`
                rightsubnetfile=`cat $siteconfpath$sitefile.conf  | grep "rightsubnet="  | sed "s/rightsubnet=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
                nblock=$(($nblock + 1))
                filconf="$sitefile-$vpn-$leftfile-$leftidfile-$leftsubnetfile-$rightfile-$rightidfile-$keyfile-$rightsubnetfile"
                vpninfo=`/bin/bash $scriptpath/scriptvpn.sh mysqlvpninfo $vpn`
                siteconfmysql=`echo $vpninfo | awk '{print $1}'`
                vpnnamemysql=`echo $vpninfo | awk '{print $2}'`
                leftmysql=`echo $vpninfo | awk '{print $3}'`
                leftidmysql=`echo $vpninfo | awk '{print $4}'`
                leftsubnetmysql=`echo $vpninfo | awk '{print $5}'`
                rightmysql=`echo $vpninfo | awk '{print $6}'`
                rightidmysql=`echo $vpninfo | awk '{print $7}'`
                rightsubnetmysql=`echo $vpninfo | awk '{print $9}'`
                secretkeymysql=`echo $vpninfo | awk '{print $8}'`
                mysqlconf="$siteconfmysql-$vpnnamemysql-$leftmysql-$leftidmysql-$leftsubnetmysql-$rightmysql-$rightidmysql-$secretkeymysql-$rightsubnetmysql"
                if [ "$filconf" = "$mysqlconf" ]
                then
                        echo "Juste reload vpn Site"
                else

# We delete Site configuration in database
	
			if [ $(chkvpnmysql $site) -gt 0 ]
			then
				echo 'DELETE FROM site_conf WHERE siteconf = "'$site'"' | $mysql_connect
			fi

# We add the new configuration

			sitemysql=$site
			nbvpn=`grep -c conn $siteconfpath$sitemysql.conf`
			nblock=1
			for vpn in `cat $siteconfpath$sitemysql.conf | grep conn | sed "s/conn //g"`
			do
				vpnmysql=$vpn
				leftmysql=`cat $siteconfpath$sitemysql.conf  | grep "left="  | sed "s/left=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == $nblock {print;}"`
				leftidmysql=`cat $siteconfpath$sitemysql.conf  | grep "leftid="  | sed "s/leftid=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
				leftsubnetmysql=`cat $siteconfpath$sitemysql.conf  | grep "leftsubnet="  | sed "s/leftsubnet=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
				rightmysql=`cat $siteconfpath$sitemysql.conf  | grep "right="  | sed "s/right=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == $nblock {print;}"`
				rightidmysql=`cat $siteconfpath$sitemysql.conf  | grep "rightid="  | sed "s/rightid=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
				keymysql=`cat $secretconffile | grep " $rightidmysql " | awk '{print $5}' | sed 's/"//g'`
				rightsubnetmysql=`cat $siteconfpath$sitemysql.conf  | grep "rightsubnet="  | sed "s/rightsubnet=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
				nblock=$(($nblock + 1))
				echo 'INSERT INTO `site_conf` (`key`, `siteconf`, `vpnname`, `left`, `leftid`, `leftsubnet`, `right`, `rightid`, `secretkey`, `rightsubnet`) VALUES (NULL , "'$sitemysql'", "'$vpnmysql'", "'$leftmysql'", "'$leftidmysql'", "'$leftsubnetmysql'", "'$rightmysql'", "'$rightidmysql'", "'$keymysql'", "'$rightsubnetmysql'");' | $mysql_connect | echo "Vpn $vpnmysql added !"
#                       echo "Vpn $vpnmysql added ! $numvpn/$nbvpnglb"
				numvpn=$(($numvpn + 1 ))
			done

# We reload secrets

			/bin/bash $scriptpath/scriptvpn.sh key >> $logfile

		fi
	done

# We reload all site's VPN

        for vpn in `cat $siteconfpath$site.conf | grep conn | sed "s/conn //g"`
        do
                echo "$site - $vpn" >> $logfile
                /bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpn
        done

;;

# IPSec status

statuipsec)

	$ipsecsetup --status

;;

# List of present site

listsites)

	ls $siteconfpath | sed "s/.conf//g"

;;

# VPN list in a single site

listvpnsite)

	cat $siteconfpath${2}.conf | grep conn | sed "s/conn //g"

;;

# We check if we can join the site

ping-loc)

reqping="select siteconf,right from site_conf;"
echo $reqping | $mysql_connect
#	ip=${2}
#	/usr/bin/fping $ip | sed "s/$ip is //g"

;;

ping-dist)

        ip=`echo 'SELECT distinct \`right\` FROM site_conf WHERE siteconf = "'${2}'"' | $mysql_connect | sed /right/d `
	ssh $pinguser@$pingsrv "/usr/bin/fping $ip" | sed "s/$ip is //g"
;;

allping-update)

        pschk="allping-update"
        if [[ `ps aux | grep $pschk | sed "/grep $pschk/d" | wc | awk '{print $1}'` -le 2 ]]
        then

# We prepare the public IP to check

		touch $scriptpath/working/ip.tmp
		touch $scriptpath/working/fpingres.tmp

		for site in `echo "SELECT distinct siteconf FROM site_conf;" | $mysql_connect | sed /siteconf/d`
		do
        		ip=`echo 'SELECT distinct \`right\` FROM site_conf WHERE siteconf = "'$site'"' | $mysql_connect | sed /right/d `
		        echo "$ip" >> $scriptpath/working/ip.tmp
		done

# We check if public IP have a ping answer

		if [ $pingtype == dist ]
		then
			scp $scriptpath/working/ip.tmp $pinguser@$pingsrv:~/
			ssh $pinguser@$pingsrv "/usr/bin/fping < ip.tmp" >> $scriptpath/working/fpingres.tmp
		else
			/usr/bin/fping < $scriptpath/working/ip.tmp >> $scriptpath/working/fpingres.tmp
		fi

# We put result in database

		for ip in `cat $scriptpath/working/fpingres.tmp | grep alive | sed s/' is alive'//g`
		do
        		site=`echo 'SELECT distinct \`siteconf\` FROM site_conf WHERE \`right\` = "'$ip'"' | $mysql_connect | sed /siteconf/d`
	        	echo "$site - alive"
	        	statvpn="alive"
		        pingdbchk=`echo 'SELECT distinct \`sites\` FROM ping WHERE sites = "'$site'"' | $mysql_connect | sed /sites/d `
        		if [ ! -z $pingdbchk ]
		        then
        		        echo 'UPDATE `ping` SET status="'$statvpn'" WHERE sites="'$site'";' | $mysql_connect
	        	else
        	        	echo 'INSERT INTO `ping` (`key`, `sites`, `status`) VALUES (NULL , "'$site'", "'$statvpn'");' | $mysql_connect
		        fi
		done
		for ip in `cat $scriptpath/working/fpingres.tmp | grep unreachable | sed s/' is unreachable'//g`
		do
	        	site=`echo 'SELECT distinct \`siteconf\` FROM site_conf WHERE \`right\` = "'$ip'"' | $mysql_connect | sed /siteconf/d`
	        	echo "$site - unreachable"
		        statvpn="unreachable"
        		pingdbchk=`echo 'SELECT distinct \`sites\` FROM ping WHERE sites = "'$site'"' | $mysql_connect | sed /sites/d `
		        if [ ! -z $pingdbchk ]
        		then
	        	        echo 'UPDATE `ping` SET status="'$statvpn'" WHERE sites="'$site'";' | $mysql_connect
	        	else
		                echo 'INSERT INTO `ping` (`key`, `sites`, `status`) VALUES (NULL , "'$site'", "'$statvpn'");' | $mysql_connect
        		fi
		done
		rm $scriptpath/working/ip.tmp
		rm $scriptpath/working/fpingres.tmp
	fi
;;

singlepingupdate)
	site=${2}
	ip=`echo 'SELECT distinct \`right\` FROM site_conf WHERE siteconf = "'$site'"' | $mysql_connect | sed /right/d `
        if [ $pingtype == dist ]
        then
        	statvpn=`ssh $pinguser@$pingsrv "/usr/bin/fping $ip" | sed "s/$ip is //g"`
        else
                statvpn=`/usr/bin/fping $ip | sed "s/$ip is //g"`
        fi
        echo 'UPDATE `ping` SET status="'$statvpn'" WHERE sites="'$site'";' | $mysql_connect
;;

# Check status of a single VPN

updatevpnstat)

        pschk="updatevpnstat"
#        if [[ `ps aux | grep $pschk | sed "/grep $pschk/d" | wc | awk '{print $1}'` -le 5 ]]
#        then

		if [ $scriptstat == prod ]
		then
		        actvpn=`$ipsecauto --status  | grep "IPsec SA established" | awk '{print $3}' | sed "s/:.*//g" | sed 's/"//g' | sort | uniq -c`
		else
		        actvpn=`cat vpnact.txt`
		fi

		declare -A ARRAY
		i=0
		j=0
		for vpn in $actvpn
		do
			ARRAY["$i:$j"]=$vpn
			j=$(($j + 1))

			if [ $j == 2 ];then
				j=0
				i=$(($i + 1))
			fi
		done
		cpt=0
		i=$(($i - 1))

		for cpt in 0 `seq $i`
		do
			qtyvpn=${ARRAY["$cpt:0"]}
			vpnup=${ARRAY["$cpt:1"]}
			req=$req' INSERT INTO `statusvpn` (`key`, `qtyvpn`, `vpn`) VALUES (NULL , "'$qtyvpn'", "'$vpnup'");'
		done

		echo 'TRUNCATE TABLE  `statusvpn`' | $mysql_connect
		echo $req | $mysql_connect
#	fi
;;

statuvpn)

	vpn=${2}
	statvpn=$(chkvpn);
echo $statvpn
	if [ -z $statvpn ] 
	then
                echo "noestablished"
	else
                if [ $statvpn == established ]
                then
                        echo "established"
                else
                        echo "noestablished"
                fi
	fi
;;

# Check if a VPN have been allready ascked to restart in last 5 min

checkreloadedvpn)

	vpn=${2}
	if [ -f $reloadedvpn ]
	then
		checkvpnreload=`grep $vpn $reloadedvpn`
		if [ -z $checkvpnreload ]
		then
			echo "notreloaded"
		else
			echo "reloaded"
		fi
	fi
;;

# Clean reloaded vpn temp file

cleanreloadedvpn)
	pschk="cleanreloadedvpn"
	if [[ `ps aux | grep $pschk | sed "/grep $pschk/d" | wc | awk '{print $1}'` -le 4 ]]
	then
	        if [ -f $reloadedvpn ]
        	then
			for reloaded in `cat $reloadedvpn`
			do
        	        	deletetime=$((`echo $reloaded | awk -F "--" '{ print $1 }'`+5))
				site=`echo $reloaded | awk -F "--" '{ print $2 }'`
				acttime=$((10`date "+%H"`*60+10`date "+%M"`))
					echo "$deletetime - $acttime"
				if [ $acttime -ge $deletetime ] 
				then
			        	sed -i '/'$site'/d' $reloadedvpn
				fi
			done
		fi
	fi

;;

# All site reload

allsitereload)

	for site in `ls $siteconfpath | egrep '\.conf$' | sed "s/.conf//g"`
	do
		/bin/bash $scriptpath/scriptvpn.sh reloadsite $site
	done

;;

# All VPN stop

allvpnstop)

        for vpn in `cat $siteconfpath*.conf | grep conn | sed 's/conn //g'`
        do
                /bin/bash $scriptpath/scriptvpn.sh stopvpn $vpn
        done

;;

# Site restart

restartsite)
	site=${2}
	usrlvl=${3}
        for var in siterestart $site $usrlvl
        do
                echo $var >> working/$site.task
        done
;;
# Site reload

reloadsite)

	if [ `/bin/bash $scriptpath/scriptvpn.sh checkreloadedvpn ${2}` == 'reloaded' ]
	then
		echo "VPN reaload still in progress..."
	else
        	pschk="reloadsite"
	        if [[ `ps aux | grep $pschk | sed "/grep $pschk ${2}/d" | wc | awk '{print $1}'` -le 5 ]]
        	then
			echo "$((10`date "+%H"`*60+10`date "+%M"`))--${2}">> $reloadedvpn
			echo "Start: Reload site ${2}"
			/bin/bash $scriptpath/scriptvpn.sh singlepingupdate ${2}
		        for vpn in `cat $siteconfpath${2}.conf | grep conn | sed "s/conn //g"`
        		do
				echo "${2} - $vpn" >> $logfile
		                /bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpn &
				PID=$!
                	        sleep 4 && kill $PID
	        	done
#			sed -i '/'${2}'/d' $reloadedvpn	
			echo "Done: Reload site ${2}"
		fi
	fi

;;

# Reload a VPN

reloadvpn)
if [ $scriptstat == prod ]
then
	$ipsecauto --delete ${2}
	sleep 1
	$ipsecauto --add ${2}
	$ipsecauto --route ${2}
	$ipsecauto --up ${2}
else
        echo "Reload Vpn ${2}" >> $logfile
fi
;;

restartvpn)
	vpn=${2}
	usrlvl=${3}
        for var in vpnrestart $vpn $usrlvl
        do
                echo $var >> working/$vpn.task
        done	
;;

# Stop a VPN

stopvpn)
	vpn=${2}
	if [ $scriptstat == prod ]
	then
		$ipsecauto --delete $vpn >> $logfile
		echo "Done: Stopping Vpn  $vpn"
	else
		echo "VPN $vpn stopped" >> $logfile
	fi
;;

# Take all information of a VPN

mysqlvpninfo)

	vpninfo=`echo 'SELECT * FROM site_conf WHERE vpnname = "'${2}'"' | $mysql_connect | sed /siteconf/d`
	siteconf=`echo $vpninfo | awk '{print $2}'`
	vpnname=`echo $vpninfo | awk '{print $3}'`
	left=`echo $vpninfo | awk '{print $4}'`
	leftid=`echo $vpninfo | awk '{print $5}'`
	leftsubnet=`echo $vpninfo | awk '{print $6}'`
	right=`echo $vpninfo | awk '{print $7}'`
	rightid=`echo $vpninfo | awk '{print $8}'`
	secretkey=`echo $vpninfo | awk '{print $9}'`
	rightsubnet=`echo $vpninfo | awk '{print $10}'`
	templateid=`echo $vpninfo | awk '{print $11}'`

if [ $scriptstat == prod ]
then
	echo "$siteconf $vpnname $left $leftid $leftsubnet $right $rightid $secretkey $rightsubnet $templateid"
else
	echo "$siteconf $vpnname $left $leftid $leftsubnet $right $rightid $secretkey $rightsubnet $templateid"
fi
;;

# Put in the in the right place vpn and secrets and start VPN this part use .task files

vpnsetup)

        pschk="vpnsetup"
        if [[ `ps aux | grep $pschk | sed "/grep $pschk/d" | wc | awk '{print $1}'` -le 4 ]]
        then
		for object in `ls $scriptpath/working/*.task`
		do
			task=`sed -n '1p' $object`
			case $task in

			porttest)
#			        site=${2}
				site=`sed -n '2p' $object`
				usrlvl=`sed -n '3p' $object`
# We get site public IP
			        sitefile=$site
			        pubip=`cat $siteconfpath$sitefile.conf  | grep "right="  | sed "s/right=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == 1 {print;}"`
#cat $siteconfpath$sitefile.conf
				/bin/bash $scriptpath/scriptvpn.sh singlepingupdate $site
				echo "Start: Task: Port test on $site - $defaultdateform"
#				echo 'INSERT INTO tchat (time,pseudo,message) VALUES (NOW(),"'$site'","'Start port scan'")' | $mysql_connect
                                if [ -e $scriptpath/log/port-$site.log ]
                                then
                                        rm $scriptpath/log/port-$site.log
                                fi
				echo "" > $scriptpath/log/port-$site.log
				echo "################" >> $scriptpath/log/port-$site.log
				echo "# From Gateway #" >> $scriptpath/log/port-$site.log
				echo "################" >> $scriptpath/log/port-$site.log
# We make a local nmap
			        nmap -p23,10022,20,21,80,69,500,4500,50,51,108 $pubip >> $scriptpath/log/port-$site.log
# We make a distant nmap

				echo "" >> $scriptpath/log/port-$site.log
				echo "" >> $scriptpath/log/port-$site.log

				echo "###############" >> $scriptpath/log/port-$site.log
				echo "# From Office #" >> $scriptpath/log/port-$site.log
				echo "###############" >> $scriptpath/log/port-$site.log
				if [ $scriptstat == prod ]

				then
					ssh $pinguser@$pingsrv "nmap -p23,10022,20,21,80,69,500,4500,50,51,108 $pubip"  >> $scriptpath/log/port-$site.log
				else
					nmap -p23,10022,20,21,80,69,500,4500,50,51,108 $pubip >> $scriptpath/log/port-$site.log
				fi
				rm $scriptpath/working/$site.task
				echo "Done: Task: Port test on $site - $defaultdateform"
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$site'","'Port scan done'","'$usrlvl'")' | $mysql_connect

			;;

			siterestart)
                                if [ -f $object ]
                                then
					site=`sed -n '2p' $object`
					usrlvl=`sed -n '3p' $object`
					if [ `/bin/bash $scriptpath/scriptvpn.sh checkreloadedvpn $site` == 'reloaded' ]
					then
					        echo "VPN reaload still in progress..."
						echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$site'","'Still reloading...'","'$usrlvl'")' | $mysql_connect
					else
					        pschk="reloadsite"
					        if [[ `ps aux | grep $pschk | sed "/grep $pschk $site/d" | wc | awk '{print $1}'` -le 5 ]]
        					then
					                echo "$((10`date "+%H"`*60+10`date "+%M"`))--$site">> $reloadedvpn
					                echo "Reload site $site - $defaultdateform"
							echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$site'","'Start reloading...'","'$usrlvl'")' | $mysql_connect
					                /bin/bash $scriptpath/scriptvpn.sh singlepingupdate $site
					                for vpn in `cat $siteconfpath$site.conf | grep conn | sed "s/conn //g"`
        					        do
					                        echo "$defaultdateform: Reload $site - $vpn" >> $logfile
					                        /bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpn &
				                                if [ $scriptstat == prod ]
				                                then
						                        PID=$!
						                        sleep 4 && kill $PID
								fi
					                done
#					                sed -i '/'$site'/d' $reloadedvpn 
							sleep 1
							echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$site'","'Site restarted'","'$usrlvl'")' | $mysql_connect
					        fi
					fi
					rm $scriptpath/working/$site.task
				fi
			;;

			vpnrestart)
                                if [ -f $object ]
                                then
					vpn=`sed -n '2p' $object`
					usrlvl=`sed -n '3p' $object`
					if [ $scriptstat == prod ]
					then
						echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$vpn'","'Start restart'","'$usrlvl'")' | $mysql_connect
					        $ipsecauto --delete $vpn
					        sleep 1
					        $ipsecauto --add $vpn
					        $ipsecauto --route $vpn
					        $ipsecauto --up $vpn
						echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$vpn'","'VPN restarted'","'$usrlvl'")' | $mysql_connect
					else
					        echo "$defaultdateform: Reload Vpn $vpn" >> $logfile
						echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$vpn'","'VPN restarted'","'$usrlvl'")' | $mysql_connect
					fi
					rm $scriptpath/working/$vpn.task
				fi
			;;

			add)
				if [ -f $object ]
        			then

# We get site information
					siteconf=`sed -n '2p' $object`
					vpnname=`sed -n '3p' $object`
					leftid=`sed -n '4p' $object`
					rightid=`sed -n '5p' $object`
					secretkey=`sed -n '6p' $object`
					usrlvl=`sed -n '7p' $object`
					echo "Start - $defaultdateform - Task: Add $siteconf - $vpnname" >> $logfile
# Setup VPN configuration

					if [ -f $siteconfpath/$siteconf.conf ]
					then
						echo "" >> $siteconfpath/$siteconf.conf
	                        	        /bin/bash $scriptpath/scriptvpn.sh key
					else
						echo "$leftid $rightid : PSK \"$secretkey\"" >> $secretconffile
						/bin/bash $scriptpath/scriptvpn.sh key
						echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$siteconf'","'Site added'","'$usrlvl'")' | $mysql_connect
					fi
					cat $scriptpath/working/$vpnname.conf >> $siteconfpath/$siteconf.conf
					/bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpnname&
                                        if [ $scriptstat == prod ]
                                        then
                                        	PID=$!
                                                sleep 4 && kill $PID
                                        fi

# We clean the working directory

					rm $scriptpath/working/$vpnname.conf
					rm $scriptpath/working/$vpnname.task
					echo "Done - $defaultdateform - Task: Add $siteconf - $vpnname" >> $logfile
					sleep 1
					echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$vpnname'","'VPN added'","'$usrlvl'")' | $mysql_connect
			
				fi
			;;
			sitedel)
				siteconf=`sed -n '2p' $object`
				rightid=`sed -n '3p' $object`
				usrlvl=`sed -n '4p' $object`
				echo "Start - $defaultdateform - Task: Site del $siteconf" >> $logfile
				for vpn in `cat $siteconfpath/$siteconf.conf | grep conn | awk '{print $2}'`
				do
					/bin/bash $scriptpath/scriptvpn.sh stopvpn $vpn
				done
				rm $siteconfpath/$siteconf.conf
				sed -i "/$rightid/d" $secretconffile
	
# We clean the working directory

				rm $scriptpath/working/$siteconf.task
				echo "Done - $defaultdateform - Task: Site del $siteconf" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$siteconf'","'Site deleted'","'$usrlvl'")' | $mysql_connect
			;;
			vpndel)
			        siteconf=`sed -n '2p' $object`
			        vpnname=`sed -n '4p' $object`
				usrlvl=`sed -n '3p' $object`
				echo "Start - $defaultdateform - Task: VPN del $siteconf - $vpnname" >> $logfile
				if [ `cat $siteconfpath/$siteconf.conf | grep conn | wc | awk '{print $1}'` == 1 ]
				then
					oldrightid=`sed -n "/$vpnname/,/rightsubnet/p" $siteconfpath/$siteconf.conf | grep "rightid=" | awk '{print $1}' | sed "s/rightid=//g"`
				        for vpn in `cat $siteconfpath/$siteconf.conf | grep conn | awk '{print $2}'`
                        		do
	                                	/bin/bash $scriptpath/scriptvpn.sh stopvpn $vpn
		                        done

	        	                rm $siteconfpath/$siteconf.conf
	                	        sed -i "/$oldrightid/d" $secretconffile
					rm $scriptpath/working/$siteconf.task
				else
					/bin/bash $scriptpath/scriptvpn.sh stopvpn $vpnname
					sed -i "/$vpnname/,/rightsubnet/d" $siteconfpath/$siteconf.conf
					rm $scriptpath/working/$vpnname.task
				fi
				echo "Done - $defaultdateform - Task: VPN del $siteconf - $vpnname" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$vpnname'","'VPN deleted'","'$usrlvl'")' | $mysql_connect
			
			;;
			update)

# We get site information

                	        siteconf=`sed -n '2p' $object`
	                        vpnname=`sed -n '3p' $object`
        	                leftid=`sed -n '4p' $object`
                	        rightid=`sed -n '5p' $object`
				rightip=`sed -n '7p' $object`
	                        secretkey=`sed -n '6p' $object`
				usrlvl=`sed -n '8p' $object`
				echo "Start - $defaultdateform - Task: Update $siteconf - $vpnname" >> $logfile

# We stop the VPN

				/bin/bash $scriptpath/scriptvpn.sh stopvpn $vpnname

				oldleftsubnet=`sed -n "/$vpnname/,/rightsubnet/p" $siteconfpath/$siteconf.conf | grep "leftsubnet=" | awk '{print $1}' | sed "s/leftsubnet=//g"`
				oldrightid=`sed -n "/$vpnname/,/rightsubnet/p" $siteconfpath/$siteconf.conf | grep "rightid=" | awk '{print $1}' | sed "s/rightid=//g"`
				oldrightip=`sed -n "/$vpnname/,/rightsubnet/p" $siteconfpath/$siteconf.conf | grep "right=" | awk '{print $1}' | sed "s/right=//g"`
				oldrightsubnet=`sed -n "/$vpnname/,/rightsubnet/p" $siteconfpath/$siteconf.conf | grep "rightsubnet=" | awk '{print $1}' | sed "s/rightsubnet=//g"`
				oldsecretkey=`cat $secretconffile | grep $oldrightid | awk '{print $5}' | sed 's/"//g'`

				if [ $oldsecretkey != $secretkey ] || [ $oldrightid != $rightid ]
				then

# We delete key information in ipsec.secrets

					sed -i "/$oldrightid/d" $secretconffile
				fi

# We delete old VPN configration
				sed  -i "/$vpnname/,/rightsubnet/d" $siteconfpath/$siteconf.conf
# Add new VPN configuration

                        	if [ -f $siteconfpath/$siteconf.conf ]
	                        then
        		                echo "" >> $siteconfpath/$siteconf.conf
                	        fi

                        	if [ $oldsecretkey != $secretkey ]  || [ $oldrightid != $rightid ]
	                        then

        	                	echo "$leftid $rightid : PSK \"$secretkey\"" >> $secretconffile
                	                /bin/bash $scriptpath/scriptvpn.sh key
                        	fi

				sed -i "s/right=$oldrightip/right=$rightip/g" $siteconfpath/$siteconf.conf >> $logfile
				sed -i "s/rightid=$oldrightid/rightid=$rightid/g" $siteconfpath/$siteconf.conf >> $logfile

                	        cat $scriptpath/working/$vpnname.conf >> $siteconfpath/$siteconf.conf
				sed -i '/^$/d' $siteconfpath/$siteconf.conf
	                        /bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpnname&
                                if [ $scriptstat == prod ]
                                then
                                        PID=$!
	                                sleep 4 && kill $PID
                                fi

# We clean the working directory

        	                rm $scriptpath/working/$vpnname.conf
                	        rm $scriptpath/working/$vpnname.task
				echo "Done - $defaultdateform - Task: Update $siteconf - $vpnname" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'$vpnname'","'VPN updated'","'$usrlvl'")' | $mysql_connect
        	        ;;

			filetobase)

				date1=`date`
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'File to base'","'Start'","'5'")' | $mysql_connect
				sed -i "/filetobase/d" $scriptpath/working/todo.task
# We make a full backup
	        	        echo "Start - $defaultdateform - Task: Update from file" >> $logfile
				/bin/bash $scriptpath/scriptvpn.sh fullbackup file  >> $logfile
				echo "Start - $defaultdateform - Task: Update from file - Stoping VPN" >> $logfile
#				/bin/bash $scriptpath/scriptvpn.sh allvpnstop >> $logfile
				echo "Done - $defaultdateform - Task: Update from file - Stoping VPN" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'File to base'","'VPN stoped'","'5'")' | $mysql_connect

# Clear data base

        	        	echo 'TRUNCATE TABLE  `statusvpn`' | $mysql_connect
	        	        echo 'TRUNCATE TABLE  `site_conf`' | $mysql_connect

# Put back all configuration from site configuration file

	        	        /bin/bash $scriptpath/scriptvpn.sh mysqlini >> $logfile

# Reload all site

				echo "Start - $defaultdateform - Task: Update from file - reaload VPN" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'File to base'","'VPN Started'","'5'")' | $mysql_connect
#                		/bin/bash $scriptpath/scriptvpn.sh allsitereload >> $logfile

# Check VPN status

		                /bin/bash $scriptpath/scriptvpn.sh updatevpnstat >> $logfile
				echo "Done - $defaultdateform - Task: Update from file - $date1" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'File to base'","'Done'","'5'")' | $mysql_connect
			;;

			basetofile)
				date1=`date`
				echo "Start - $defaultdateform - Task: Update from data base" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'Base to file'","'Start'","'5'")' | $mysql_connect
				sed -i "/basetofile/d" $scriptpath/working/todo.task
                	        /bin/bash $scriptpath/scriptvpn.sh fullbackup base >> $logfile
				for siteconf in `echo 'SELECT distinct \`siteconf\` FROM site_conf' | $mysql_connect | sed /siteconf/d`
				do
					vpnlist=`echo 'SELECT \`vpnname\` FROM site_conf WHERE siteconf = "'$siteconf'"' | $mysql_connect | sed /vpnname/d`

# We stop all VPN of the site

					for vpn in `echo $vpnlist`
					do
						/bin/bash $scriptpath/scriptvpn.sh stopvpn $vpn >> $logfile
					done

# We delete old configuration file

					oldrightid=`sed -n "/$siteconf/,/rightsubnet/p" $siteconfpath/$siteconf.conf | grep "rightid=" | awk '{print $1}' | sed "s/rightid=//g" | uniq`
					echo " oldrightid: - $oldrightid"  >> $logfile
					rm $siteconfpath/$siteconf.conf
					sed -i "/$oldrightid/d" $secretconffile

# We creat new configuration file

					for vpn in `echo $vpnlist`
					do
# We get all VPN information
						vpninfo=`/bin/bash $scriptpath/scriptvpn.sh mysqlvpninfo $vpn`
						siteconf=`echo $vpninfo | awk '{print $1}'`
						vpnname=`echo $vpninfo | awk '{print $2}'`
						left=`echo $vpninfo | awk '{print $3}'`
						leftid=`echo $vpninfo | awk '{print $4}'`
					        leftsubnet=`echo $vpninfo | awk '{print $5}'`
					        right=`echo $vpninfo | awk '{print $6}'`
					        rightid=`echo $vpninfo | awk '{print $7}'`
					        rightsubnet=`echo $vpninfo | awk '{print $9}'`
						secretkey=`echo $vpninfo | awk '{print $8}'`
						usedtemplate=`echo $vpninfo | awk '{print $10}'`
						echo "$siteconf - $vpnname - $left - $leftid - $leftsubnet - $right - $rightid - $rightsubnet - $secretkey - $usedtemplate" >> $logfile
# We setup new key

	        	                        if [ -f $siteconfpath/$siteconf.conf ]
        	        	                then
                	        	                echo "" >> $siteconfpath/$siteconf.conf
                        	        	        /bin/bash $scriptpath/scriptvpn.sh key
	                                	else
        	                                	echo "$leftid $rightid : PSK \"$secretkey\"" >> $secretconffile
							/bin/bash $scriptpath/scriptvpn.sh key
	                	                fi

					        cp $scriptpath/template/vpntemplate-$usedtemplate.conf $scriptpath/working/$vpnname.conf
					        sed -i "s/VPNNAME/$vpnname/g" $scriptpath/working/$vpnname.conf
					        sed -i "s/LEFT/$left/g" $scriptpath/working/$vpnname.conf
					        sed -i "s/LID/$leftid/g" $scriptpath/working/$vpnname.conf
					        sed -i "s%LSUBNET%$leftsubnet%g" $scriptpath/working/$vpnname.conf
					        sed -i "s/RIGHT/$right/g" $scriptpath/working/$vpnname.conf
				        	sed -i "s/RID/$rightid/g" $scriptpath/working/$vpnname.conf
					        sed -i "s%RSUBNET%$rightsubnet%g" $scriptpath/working/$vpnname.conf
					        sed -i "s/VPNNAME/$vpnname/g" $scriptpath/working/$vpnname.conf
	        	                        cat $scriptpath/working/$vpnname.conf >> $siteconfpath/$siteconf.conf
        	        	                /bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpnname >> $logfile

# We clean the working directory

	                        	        rm $scriptpath/working/$vpnname.conf
					done

# We start all VPN of the site

        	                        for vpn in `echo $vpnlist`
                	                do
                        	                /bin/bash $scriptpath/scriptvpn.sh reloadvpn $vpn >> $logfile
                                	done
				done
				echo "Done - $defaultdateform - Task: Update from data base - $date1" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'Base to file'","'Done'","'5'")' | $mysql_connect

			;;
			restore)
				date1=`date`
                        	echo "Start - $defaultdateform - Task: restore all VPN" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'Restore'","'Start'","'5'")' | $mysql_connect
        	                sed -i "/restore/d" $scriptpath/working/todo.task >> $logfile

# We stop all VPN

				/bin/bash $scriptpath/scriptvpn.sh allvpnstop >> $logfile

# We restore all file and folder

				cp -rf $scriptpath/upload/files/ipsec.secrets $secretconffile >> $logfile
				cp -rf $scriptpath/upload/folder/ipsec.d/sites/* $siteconfpath >> $logfile

# We restore the data base

				echo 'TRUNCATE TABLE  `statusvpn`' | $mysql_connect >> $logfile
				echo 'TRUNCATE `ping`' | $mysql_connect >> $logfile
				echo 'TRUNCATE `site_conf`' | $mysql_connect >> $logfile
				echo 'TRUNCATE `template`' | $mysql_connect >> $logfile

				mysql -h $mysqldatabasehost -u $mysqldatabaseuser -p$mysqldatabasepwd $mysqldatabase < $scriptpath/upload/bases/$mysqldatabase.sql

# We reload secrets

				/bin/bash $scriptpath/scriptvpn.sh key >> $logfile

# We reload all VPN

				/bin/bash $scriptpath/scriptvpn.sh allsitereload >> $logfile

# We clean upload forler

				rm -rf $scriptpath/upload/* >> $logfile


				echo "Done - $defaultdateform - Task: restore all VPN - $date1" >> $logfile
				echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'Restore'","'Done'","'5'")' | $mysql_connect
			;;
			esac
		done
	fi
;;

# Update a VPN-site

vpnupdate)

# We load each site configuration

        siteconf=${2}
        vpnname=${3}
        left=${4}
        leftid=${5}
        leftsubnet=${6}
        right=${7}
        rightid=${8}
        rightsubnet=${9}
        secretkey=${10}
	usrlvl=${11}

if [ $scriptstat == prod ]
then
        echo ""
else
       echo "$siteconf - $vpnname - $left - $leftid - $leftsubnet - $right - $rightid - $rightsubnet - $secretkey"
fi

# We prepare site conf file

        cp template/vpntemplate.conf working/$vpnname.conf
        sed -i "s/VPNNAME/$vpnname/g" working/$vpnname.conf
        sed -i "s/LEFT/$left/g" working/$vpnname.conf
        sed -i "s/LID/$leftid/g" working/$vpnname.conf
        sed -i "s%LSUBNET%$leftsubnet%g" working/$vpnname.conf
        sed -i "s/RIGHT/$right/g" working/$vpnname.conf
        sed -i "s/RID/$rightid/g" working/$vpnname.conf
        sed -i "s%RSUBNET%$rightsubnet%g" working/$vpnname.conf
        sed -i "s/VPNNAME/$vpnname/g" working/$vpnname.conf

# We prepare a task file to be used by the script to put configuration file and secret key in the right place

        for var in update $siteconf $vpnname $leftid $rightid $secretkey $right $usrlvl
        do
                echo $var >> working/$vpnname.task
        done

;;

# Add a site

siteadd)

# We load each site configuration

	siteconf=${2}
	vpnname=${3}
	left=${4}
	leftid=${5}
 	leftsubnet=${6}
	right=${7}
 	rightid=${8}
	rightsubnet=${9}
 	secretkey=${10}
	usedtemplate=${11}
	usrlvl=${12}
if [ $scriptstat == prod ]
then
        echo ""
else
	echo "$siteconf - $vpnname - $left - $leftid - $leftsubnet - $right - $rightid - $rightsubnet - $secretkey - $usedtemplate - $usrlvl"
fi

# We prepare site conf file

	cp template/vpntemplate-$usedtemplate.conf working/$vpnname.conf
	sed -i "s/VPNNAME/$vpnname/g" working/$vpnname.conf
	sed -i "s/LEFT/$left/g" working/$vpnname.conf
	sed -i "s/LID/$leftid/g" working/$vpnname.conf
	sed -i "s%LSUBNET%$leftsubnet%g" working/$vpnname.conf
	sed -i "s/RIGHT/$right/g" working/$vpnname.conf
	sed -i "s/RID/$rightid/g" working/$vpnname.conf
	sed -i "s%RSUBNET%$rightsubnet%g" working/$vpnname.conf
	sed -i "s/VPNNAME/$vpnname/g" working/$vpnname.conf

# We prepare a task file to be used by the script to put configuration file and secret key in the right place
	
	for var in add $siteconf $vpnname $leftid $rightid $secretkey $usrlvl
	do
		echo $var >> working/$vpnname.task
	done
;;

# Add a VPN

vpnadd)

# We load each vpn configuration

        siteconf=${2}
        vpnname=${3}
        left=${4}
        leftid=${5}
        leftsubnet=${6}
        right=${7}
        rightid=${8}
        rightsubnet=${9}
        secretkey=${10}
	usedtemplate=${11}
	usrlvl=${12}

if [ $scriptstat == prod ]
then
        echo ""
else
    	echo "$siteconf - $vpnname - $left - $leftid - $leftsubnet - $right - $rightid - $rightsubnet - $secretkey"
fi

# We prepare site conf file

        cp template/vpntemplate-$usedtemplate.conf working/$vpnname.conf
        sed -i "s/VPNNAME/$vpnname/g" working/$vpnname.conf
        sed -i "s/LEFT/$left/g" working/$vpnname.conf
        sed -i "s/LID/$leftid/g" working/$vpnname.conf
        sed -i "s%LSUBNET%$leftsubnet%g" working/$vpnname.conf
        sed -i "s/RIGHT/$right/g" working/$vpnname.conf
        sed -i "s/RID/$rightid/g" working/$vpnname.conf
        sed -i "s%RSUBNET%$rightsubnet%g" working/$vpnname.conf
        sed -i "s/VPNNAME/$vpnname/g" working/$vpnname.conf

# We prepare a task file to be used by the script to put configuration file and secret key in the right place

        for var in add $siteconf $vpnname $leftid $rightid $secretkey $usrlvl
        do
                echo $var >> working/$vpnname.task
        done
;;

# Delete a site

sitedelete)
        siteconf=${2}
	rightid=${3};
	usrlvl=${4}
        for var in sitedel $siteconf $rightid $usrlvl
        do
                echo $var >> working/$siteconf.task
        done
;;

# Delete a vpn

vpndelete)
        siteconf=${2}
        rightid=${4};
	vpnname=${3}
	usrlvl=${5}
	
        for var in vpndel $siteconf $rightid $vpnname $usrlvl
        do
                echo $var >> working/$vpnname.task
        done

;;

# Reload preshared key

key)
if [ $scriptstat == prod ]
then
        $ipsecbin secrets
else
	echo "secrets reload"
fi

;;

# Load site configuration file in database

mysqlini)

numvpn=1
nbvpnglb=`cat $siteconfpath*.conf | grep -c conn`
	for site in `ls $siteconfpath | egrep '\.conf$' | sed "s/.conf//g"`
	do
		sitemysql=$site
		nbvpn=`grep -c conn $siteconfpath$sitemysql.conf`
		nblock=1
		for vpn in `cat $siteconfpath$sitemysql.conf | grep conn | sed "s/conn //g"`
		do
			vpnmysql=$vpn
                        leftmysql=`cat $siteconfpath$sitemysql.conf  | grep "left="  | sed "s/left=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == $nblock {print;}"`
                        leftidmysql=`cat $siteconfpath$sitemysql.conf  | grep "leftid="  | sed "s/leftid=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
                        leftsubnetmysql=`cat $siteconfpath$sitemysql.conf  | grep "leftsubnet="  | sed "s/leftsubnet=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
			rightmysql=`cat $siteconfpath$sitemysql.conf  | grep "right="  | sed "s/right=//g" | sed "s/ //g" | sed "s/\t//g" | awk "NR == $nblock {print;}"`
			rightidmysql=`cat $siteconfpath$sitemysql.conf  | grep "rightid="  | sed "s/rightid=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
			keymysql=`cat $secretconffile | grep " $rightmysql " | awk '{print $5}' | sed 's/"//g'`
			rightsubnetmysql=`cat $siteconfpath$sitemysql.conf  | grep "rightsubnet="  | sed "s/rightsubnet=//g" | sed "s/ //g" | sed "s/\t//g"  | awk "NR == $nblock {print;}"`
			nblock=$(($nblock + 1))
			echo 'INSERT INTO `site_conf` (`key`, `siteconf`, `vpnname`, `left`, `leftid`, `leftsubnet`, `right`, `rightid`, `secretkey`, `rightsubnet`) VALUES (NULL , "'$sitemysql'", "'$vpnmysql'", "'$leftmysql'", "'$leftidmysql'", "'$leftsubnetmysql'", "'$rightmysql'", "'$rightidmysql'", "'$keymysql'", "'$rightsubnetmysql'");' | $mysql_connect | echo "Vpn $vpnmysql added ! $numvpn/$nbvpnglb"
#sleep 1
echo 'INSERT INTO tchat (time,pseudo,message,user_level) VALUES (NOW(),"'Vpn $vpnmysql added !'","'$numvpn/$nbvpnglb'","'5'")' | $mysql_connect
#			echo "Vpn $vpnmysql added ! $numvpn/$nbvpnglb"
			numvpn=$(($numvpn + 1 ))
		done
	done
#echo $reqsite | $mysql_connect | echo "done !"

;;

fullbackup)
	if [ ${2} ]
	then
		case ${2} in
			file)
				backupfilename="save-filebackup-`date +"%y-%m-%d"`.tar.gz"
			;;
			base)
				backupfilename="save-basebackup-`date +"%y-%m-%d"`.tar.gz"
			;;
		esac
	fi
	

	if [ -d $tmpfolder ]
	then
        	tmpfoldertest=1
	else
        	mkdir $tmpfolder
	        tmpfoldertest=1
	fi

	if [ -d $tmpfolder/files/ ]
	then
        	echo "Tmp file OK"
	else
        	mkdir $tmpfolder/files/
	fi


	if [ -d $tmpfolder/folder/ ]
	then
        	echo "Tmp folder OK"
	else
        	mkdir $tmpfolder/folder/
	fi

	if [ -d $tmpfolder/bases/ ]
	then
        	echo "Tmp file OK"
	else
        	mkdir $tmpfolder/bases/
	fi
	if [ $tmpfoldertest == 1 ]
	then

# Files backup
	if [ `grep -c [[:alpha:]]<<<${filetobackup}` -ne 0 ]
	then
	echo "File backup"
		for filetob in $filetobackup
        	do
                	if [ -e $filetob ]
	                then

	        	        cp $filetob $tmpfolder/files/
		
	                else
        	                echo "File $filetob does not exist"
                	fi
	        done
	fi

# Folder backup
	if [ `grep -c [[:alpha:]]<<<${foldertobackup}` -ne 0 ]
	then
		echo "Folder backup"
	        for foldertob in $foldertobackup
        	do
	                if [ -d $foldertob ]
        	        then

                	        cp -rf $foldertob $tmpfolder/folder/

	                else
        	                echo "Folder $foldertob does not exist"
                	fi
	        done
	fi
# Database backup (mysql)

	if [ `grep -c [[:alpha:]]<<<${mysqldatabase}` -ne 0 ]
	then
		echo "Database backup"
	        for base in $mysqldatabase
        	do
                	/usr/bin/mysqldump -u $mysqldatabaseuser -p$mysqldatabasepwd -h $mysqldatabasehost $base > $tmpfolder/bases/$base.sql
	        done
	fi

# Compress backup

	cd $tmpfolder
	tar -czvf $backupfilename folder files bases
	cd $scriptpath
	mv $tmpfolder/$backupfilename $scriptpath/backup

# Keep $saveqty last backup

	savelist=`ls $scriptpath/backup`
	for save in $savelist
	do
        	echo $save >> $tmpfolder/savelist.lst
	done
	cp $tmpfolder/savelist.lst $tmpfolder/savelist.bak

	tail -n $saveqty $tmpfolder/savelist.lst > $tmpfolder/savelist2.lst

	for save in `cat $tmpfolder/savelist2.lst`
	do
        	sed -i "/^$save/"'d' $tmpfolder/savelist.lst
	done

	for save in `cat $tmpfolder/savelist.lst`
	do
        	echo "$save deleted"
	        rm $scriptpath/backup/$save
	done

# Clean temp file and folder
	rm -rf $tmpfolder/*
	fi

;;

mysqlupdate)

	from=${2}

	case $from in

# From file to data base
	file)

		echo filetobase >> working/todo.task	

	;;

# From data base to file
	base)
		echo basetofile >> working/todo.task
	;;
	esac

;;

mysqlinstall)

	echo "Mysql user login"
	read user
	echo "Mysql user password"
	read upwd
	echo "Data base name"
	read db
	echo "Mysql root password"
	read rootpwd
	echo 'CREATE USER '$user'@'localhost' IDENTIFIED BY  "'$upwd'"; CREATE DATABASE IF NOT EXISTS  `'$db'`; GRANT ALL PRIVILEGES ON  `'$db'` . * TO  '$user'@'localhost'; FLUSH PRIVILEGES;' | /usr/bin/mysql -A -uroot -p$rootpwd
	/usr/bin/mysql -A -uroot -p$rootpwd -D$db < ipsec.sql

	for files in connect.php tchat.php
	do
		cp $scriptpath/inifiles/$files $scriptpath/$files
		sed -i "s/USERAPP/$user/g" $files
		sed -i "s/PWDAPP/$upwd/g" $files
		sed -i "s/DBAPP/$db/g" $files
	done
;;

install)
	echo "" > $logfile
	chmod 777 $logfile
	echo "Mysql configuration..."
	/bin/bash $scriptpath/scriptvpn.sh mysqlinstall
	echo "Mysql configuration done !"
	echo "Cron configuration"
	(crontab -l; echo "* * * * * bash $scriptpath/scriptvpn.sh cleanreloadedvpn") | crontab -
#	(crontab -l; echo "* * * * * bash $scriptpath/scriptvpn.sh vpnsetup") | crontab -
	(crontab -l; echo "*/30 * * * * bash /var/www/vpngui/scriptvpn.sh allping-update") | crontab -
	echo "#!/bin/bash" > /usr/bin/scriptvpnd
	echo "exec >>/var/log/vpngui.log 2>&1" >> /usr/bin/scriptvpnd
	echo "while :; do" >> /usr/bin/scriptvpnd
	echo "sleep 2" >> /usr/bin/scriptvpnd
	echo "/bin/bash $scriptpath/scriptvpn.sh vpnsetup" >> /usr/bin/scriptvpnd
	echo "done" >> /usr/bin/scriptvpnd
	cp $scriptpath/inifiles/init.d/scriptvpnd /etc/init.d/scriptvpnd
	chmod +x /etc/init.d/scriptvpnd
	chmod +x /usr/bin/scriptvpnd
	update-rc.d scriptvpnd defaults
	cp $scriptpath/install/vpngui /etc/logrotate.d/
	mkdir $tmpfolder
	chown -R www-data: $tmpfolder
	chown -R www-data: working
	touch $scriptpath/tmp/status.log
	chmod 774 $scriptpath/tmp/status.log
	chown :www-data $scriptpath/tmp/status.log
	chown :www-data scriptvpn.sh
	/etc/init.d/scriptvpnd start
	echo "Default admin user login are: admin/admin123"
;;

esac
