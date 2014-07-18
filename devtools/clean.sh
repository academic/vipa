#!/usr/bin/env bash
echo "Run from project root folder"
bold=`tput bold`
normal=`tput sgr0`
echo
echo
read -p "${bold}WARNING!!${normal} Your database will be purged !!! (Y/n) "
echo 
if [[ $REPLY =~ ^[Yy]$ ]] || [[ $REPLY == "" ]];
then
	read -p  "And a clean ojs will be installed. ${bold}Are you sure?${normal} (Y/n)? "
	echo
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
		#danger zone
		mysql -uroot -proot ojssf -e "drop database ojssf"
		if [[ $# -gt 0 ]]
		then
			if [[ $1 ==  "--composer" ]]
			then
				echo "running composer update"
				composer update
			fi
			if [[ $1 ==  "--removecache" ]]
                        then
                                echo "deleting app/cache folder"
                                rm -rf app/cache
                        fi
		fi
		
		php app/console ojs:install
		php app/console doctrine:fixtures:load --append -v
		php app/console doctrine:mongodb:fixtures:load --append -v
	fi
fi
