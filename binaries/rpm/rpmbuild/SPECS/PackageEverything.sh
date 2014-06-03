#!/bin/bash

pkg="ctracker-client-0.02-1.noarch.rpm"
name="ctracker"
version="0.02"
git="/home/matheus/ctracker"

function zip(){
#create new tar.gz file from the client folder
	cd ~/rpmbuild/$name/
	tar -zcvf $name-client-$version.tar.gz $name-client-$version/
	mv $name-client-$version.tar.gz ../SOURCES/

}

function clear(){

	cd ~/rpmbuild/
	rm -r BUILD
	rm -r BUILDROOT
	mkdir BUILD
	mkdir BUILDROOT
	cd ~/rpmbuild/RPMS/noarch/
	rm $pkg

	rm rh5.9/$pkg
	rm rh6.5/$pkg
}


function build(){

	cd ~/rpmbuild/SPECS

	#build for rh 5.9
	rpmbuild -bb --define "_binary_filedigest_algorithm  1"  --define "_binary_payload 1" rh5.9-ctracker.spec 
	mv ../RPMS/noarch/$pkg ../RPMS/noarch/rh5.9/

	#build for rh 6.5
	rpmbuild -ba ctracker.spec
	mv ../RPMS/noarch/$pkg ../RPMS/noarch/rh6.5/
}

#zip new version
if [ "$1" == "zip" ]; then

	zip

elif [ "$1" == "clear" ];then

	clear

elif [ "$1" == "build" ];then

	build

elif [ "$1" == "git-clean" ];then

	cd $git/binaries/

	git rm -r rpm/rpmbuild
	git commit -m "old binaries"
	git push origin master

elif [ "$1" == "git-add" ];then

	cd $git/binaries/

	mkdir rpm
	cd rpm
#	tar -zcvf rpmbuild.tar.gz ~/rpmbuild
	
	cp -rf ~/rpmbuild/ .

	git add rpmbuild

	cd rpmbuild

	git add *

	git commit -m "new binaries"
	git push origin master

else

	echo "Usage: ./PackageEverything.sh zip|clear|build"
fi



