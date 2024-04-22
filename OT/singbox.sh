#!/bin/bash
sudo mkdir -p /root/xpanel_singbox
uname=$(uname -i)

if [[ $uname == x86_64 ]]; then
  curl -sL -o /usr/bin/sbx https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/sbx.sh.x
  chmod +x /usr/bin/sbx && sbx
	wait
	curl -sL -o /usr/local/bin/sb_xpanel https://github.com/xpanel-cp/XPanel-SSH-User-Management/raw/master/sb_xpanel_86_64.sh.x
	chmod +x /usr/local/bin/sb_xpanel
	echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/sb_xpanel' | sudo EDITOR='tee -a' visudo
  wait
	curl -sL -o /root/xpanel_singbox/xtraffic.sh.x https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/xtraffic.sh.x
	sudo chown -R root:root /root/xpanel_singbox/xtraffic.sh.x
	chmod +rx /root/xpanel_singbox/xtraffic.sh.x
 clear
fi

if [[ $uname == aarch64 ]]; then
  curl -sL -o /usr/bin/sbx https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/OT/arch64_sbx.sh.x
  chmod +x /usr/bin/sbx && sbx
	wait
	curl -sL -o /usr/local/bin/sb_xpanel https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/OT/arch64_sb_xpanel.sh.x
	chmod +x /usr/local/bin/sb_xpanel
	echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/sb_xpanel' | sudo EDITOR='tee -a' visudo
  wait
	curl -sL -o /root/xpanel_singbox/xtraffic.sh.x https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/OT/arch64_xtraffic.sh.x
	sudo chown -R root:root /root/xpanel_singbox/xtraffic.sh.x
	chmod +rx /root/xpanel_singbox/xtraffic.sh.x
 clear
fi
