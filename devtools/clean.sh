#!/usr/bin/env bash
echo "Run from project root folder"
bold=`tput bold`
normal=`tput sgr0`
echo "${bold}WARNING!!${normal} Your database will be purged! (Y/n) :"
read CHECK1
if [[ $CHECK1 =~ ^[Yy]$ ]] || [[ $CHECK1 == "" ]];
then
	echo  "A clean ojs will be installed. ${bold}Are you sure?${normal} (Y/n)? "
	read CHECK2
	echo
	if [[ $CHECK2 =~ ^[Yy]$ ]] || [[ $CHECK1 == "" ]]
	then
		#danger zone
		echo "Type your ojs databasename :"
		read DBOJS
		mysql -uroot -proot -e "drop database $DBOJS"
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
