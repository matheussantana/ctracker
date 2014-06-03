# Don't try fancy stuff like debuginfo, which is useless on binary-only
# packages. Don't strip binary too
# Be sure buildpolicy set to do nothing
%define        __spec_install_post %{nil}
%define          debug_package %{nil}
%define        __os_install_post %{_dbpath}/brp-compress

Summary: Cloud Computing Monitoring Tool - Client
Name: ctracker-client
Version: 0.02
Release: 1
License: GPL+
Group: Applications/System
Requires: curl sysstat procps redhat-lsb
BuildArch: noarch
SOURCE0 : %{name}-%{version}.tar.gz
Packager: Matheus Santana <matheusslima@yahoo.com.br>
URL: http://cloudtracker.org/

BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root

%description
#%{summary}
Ctracker is a free and open source web monitoring tool used to keep track of your remote linux machines in real time. You can get metrics and graphs that allow you to see how much your servers are consuming. This kind of tool is specially important in a cloud-like environment(for both public or private).

%prep
%setup -q

%build
# Empty section.

%install
rm -rf %{buildroot}
mkdir -p  %{buildroot}

# in builddir
cp -a * %{buildroot}


%clean
rm -rf %{buildroot}


%files
%defattr(-,root,root,-)
#%config(noreplace) %{_sysconfdir}/%{name}/%{name}.conf
#%{_bindir}/*
/bin/ctracker
/etc/init.d/ctracker
/usr/share/ctracker/ctracker.d/dhclient
/usr/share/ctracker/ctracker.d/sshd
/usr/share/ctracker/ctracker.d/httpd
/usr/share/ctracker/hardware_tracker.sh
/usr/share/ctracker/info.conf
/usr/share/ctracker/ctracker-main
/usr/share/ctracker/process_tracker.sh
/usr/share/ctracker/scripts/sshd.sh
/usr/share/ctracker/vmstat_tracker.sh

%changelog
* Mon Jun 2 2014  Matheus Santana <matheusslima@yahoo.com.br> 0.02-1
- First Build
