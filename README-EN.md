<p align="center">
<picture>
<img width="160" height="160"  alt="XPanel" src="https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/xlogo.png">
</picture>
  </p> 
<h1 align="center"/>X Panel</h1>
<h6 align="center">X Panel SSH User Management<h6>
<p align="center">
<img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/xpanel-cp/XPanel-SSH-User-Management">
</p>
 
<p align="center">
	<a href="./README-EN.md">
	English
	</a>
	/
	<a href="./README.md">
	فارسی
	</a>
</p>

## Contents
- [Introduction](#x-panel-introduction)<br>
- [Features](#features)<br>
- [Installation guide](#installation-guide) <br>
  - [Server optimization](#server-optimization)<br>
  - [Enabling SSL](#enabling-ssl)<br>
- [Supporting us](#supporting-us-hearts)
<br> 

## X-PANEL Introduction
X-Panel is a lightweight web application for SSH accounts management. With the help of X-Panel, you can manage users and apply restrictions.	

### Features and Capabilities
:green_circle: Ability to create multiple users (unlimited)<br>
:green_circle: Restrictions on users’ traffic consumption and expiration date<br>
:green_circle: Ability to calculate the expiration date after the first connection<br>
:green_circle: Ability to set limitation for user account’s concurrent sessions<br>
:green_circle: View online users<br>
:green_circle: Ability to backup and restore users<br>
:green_circle: Telegram Bot support<br>
:green_circle: Setting optional port number for control panel access<br>
:green_circle: Fake address (Evade Censorship)<br>
:green_circle: IP blacklist (Blacklisting adult websites and …)<br>
:green_circle: API support<br>
:orange_circle: Multi-server support (Soon…)<br>


### Installation guide
Supported operating systems<br>
- **Ubuntu 18+ (recommended: Ubuntu 20)** <br>

Changing username, password and port as well as removing XPanel from the server (version 3.6 and higher)<br>
```
bash /root/xpanel.sh OR bash xpanel.sh 
```

To install the X-Panel simply input the following command in the terminal:<br>
```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/install.sh --ipv4)
```

To resolve audio and video call issues use this command:<br>
```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/fix-call.sh --ipv4)
```

### Server optimization
Use the following command to install or remove<br>
```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/TCP-Tweaker --ipv4)
```


### Enabling SSL
```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/ssl.sh --ipv4)
```
With the above command you can install SSL on the panel **(First pay attention to tips below)** <br>
1- Make sure to update the panel BEFORE installing the SSL<br>
2- Do not use any other commands to activate SSL<br>
3- Set the server’s IP in your domain or subdomain<br>
4- Input the above command in the terminal and proceed with the installation<br>
**SSL is now active on your selected port**



### Supporting us :hearts:
If X-Panel has been useful to you with supporting us you can help developing this web application.<br>

<p align="left">
	
|                    TRX                   |                       ETH                         |                    Litecoin                       |
| ---------------------------------------- |:-------------------------------------------------:| -------------------------------------------------:|
| ```TYQraQ5JJXKyVD6BpTGoDYNhiLbFRfzVtV``` |  ```0x6cc08b2057EfAe4d76Af531e145DeEd4B73c9D7e``` | ```ltc1q6gq4espx74lp6jvhmr0jmxlu4al0uwemmzwdv4``` |	

</p>
	


