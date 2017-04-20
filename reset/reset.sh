#!/bin/bash

#--------------------------------------------------
# Config
#--------------------------------------------------

	set -u;

	FILE="$0";

	cd `dirname "${FILE}"`;

#--------------------------------------------------
# Changed files
#--------------------------------------------------

	FILES_CHANGED=`git diff --name-only`;
	if [ -n "${FILES_CHANGED}" ]; then
		echo "The following changes will be reset:";
		echo;
		echo "${FILES_CHANGED}" | awk '{print "  " $0;}';
		echo;
		echo -n "Would you like to continue? ";
		read KEY;
		if [ "${KEY}" != "y" ] && [ "${KEY}" != "Y" ] && [ "${KEY}" != "Yes" ] && [ "${KEY}" != "yes" ] && [ "${KEY}" != "YES" ]; then
			echo "Cancelled";
			echo;
			exit;
		fi
		echo;
		git checkout "../";
	fi

#--------------------------------------------------
# Update database
#--------------------------------------------------

	php "./reset.php";

#--------------------------------------------------
# File permissions
#--------------------------------------------------

	chmod 777 "../public/security/uploads/profile-pictures/";

	find "../public/security/uploads/profile-pictures/" -type f -print0 | xargs -0 chmod 666;
