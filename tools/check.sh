#!/usr/bin/env bash
safeRunCommand() {
	typeset cmnd="$*";typeset ret_code;echo cmnd=$cmnd;
	eval $cmnd;ret_code=$?
	if [ $ret_code != 0 ]; then
  		printf "\n\nError : [%d] when executing command: '$cmnd'." 
                printf "Please check notes above. \n\n\n" 
	fi
}

check1="php app/check.php"

safeRunCommand "$check1"
