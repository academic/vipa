#!/usr/bin/env bash
echo "Run from project root folder"
bold=`tput bold`
normal=`tput sgr0`
echo
echo
read -p "${bold}WARNING!!${normal} Your database will be purged !!!  And a clean ojs will be installed. Are you sure? (Y/n)" -n 1 -r
echo    # new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
	#danger zone
	mysql -uroot -proot ojssf -e "drop database ojssf"
	sudo php app/console ojs:install
	sudo php app/console doctrine:fixtures:load --append -v
	sudo php app/console doctrine:mongodb:fixtures:load --append -v
fi
