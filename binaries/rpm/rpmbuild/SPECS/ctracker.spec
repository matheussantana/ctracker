# Don't try fancy stuff like debuginfo, which is useless on binary-only
# packages. Don't strip binary too
# Be sure buildpolicy set to do nothing
%define        __spec_install_post %{nil}
%define          debug_package %{nil}
%define        __os_install_post %{_dbpath}/brp-compress

Summary: A very simple toy bin rpm package
Name: ctracker-client
Version: 0.02
Release: 1
License: GPL+
Group: Development/Tools
BuildArch: noarch
SOURCE0 : %{name}-%{version}.tar.gz
URL: http://toybinprog.company.com/

BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root

%description
%{summary}

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
/usr/share/ctracker/ctracker.d/dhclient
/usr/share/ctracker/ctracker.d/sshd
/usr/share/ctracker/ethx.tmp
/usr/share/ctracker/hardware_tracker.sh
/usr/share/ctracker/info.conf
/usr/share/ctracker/main
/usr/share/ctracker/process_tracker.sh
/usr/share/ctracker/scripts/sshd.sh
/usr/share/ctracker/vmstat_output.txt
/usr/share/ctracker/vmstat_tracker.sh

%changelog
* Thu Apr 24 2009  Elia Pinto <devzero2000@rpm5.org> 1.0-1
- First Build
