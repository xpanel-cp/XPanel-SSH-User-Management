<p align="center">
<picture>
<img width="160" height="160"  alt="XPanel" src="https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/xlogo.png">
</picture>
  </p> 
<h1 align="center"/>XPanel</h1>
<h6 align="center">Управление пользователями SSH XPanel<h6>
<p align="center">
<img alt="GitHub all releases" src="https://img.shields.io/github/downloads/xpanel-cp/XPanel-SSH-User-Management/total">
<img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/xpanel-cp/XPanel-SSH-User-Management">
<a href="https://t.me/Xpanelssh" target="_blank">
<img alt="Telegram Channel" src="https://img.shields.io/endpoint?label=Channel&style=flat-square&url=https%3A%2F%2Ftg.sumanjay.workers.dev%2FXpanelssh&color=blue">
</a>
</p>
 
<p align="center">
	<a href="./README-EN.md">
	Английский
	</a>
	/
	<a href="./README.md">
	Персидский
	</a>
</p>


### Содержание
- [Введение](#Введение)<br>
- [Протокол](#Протокол-)<br>
- [Особенности](#особенности-)<br>
- [Установка](#Установка) <br>
  - [Оптимизация сервера](#Оптимизация-сервера)<br>
  - [Активация SSL](#Активация-SSL)<br>
- [Поддержка](#Поддержка-нас-hearts)<br>
 
## Введение <br>
XPanel - это веб-приложение для управления учетными записями SSH. С помощью веб-панели XPanel вы можете управлять пользователями и накладывать ограничения.

## Протокол <br>
Протоколы, поддерживаемые XPanel.<br>
:white_check_mark:  `SSH-DIRECT`  :white_check_mark:  `SSH-TLS` :white_check_mark:  `SSH-DROPBEAR`  :white_check_mark:  `SSH-DROPBEAR-TLS` :white_check_mark:  `SSH-WEBSOCKET` <br>  
:white_check_mark:  `SSH-WEBSOCKET-TLS` :white_check_mark:  `VMess ws`  :white_check_mark:  `VLess Reality` :white_check_mark:  `Hysteria2`  :white_check_mark:  `Tuic`

Порты 443, 80 и 8880 резервируются по умолчанию для веб-сервера. <br>
Websocket HTTP Payload<br>
`GET /ws HTTP/1.1[crlf]Host: sni.domain.com[crlf]Upgrade: websocket[crlf][crlf]` 
Websocket SSL Payload<br>
`GET wss://sni.domain.com/ws HTTP/1.1[crlf]Host: sni.domain.com[crlf]Upgrade: websocket[crlf][crlf]` <br>

## Особенности <br>
:green_circle: Создание пользователя без ограничений <br>
:green_circle: Наложение ограничений на объем трафика и срок действия<br>
:green_circle: Возможность расчета срока действия при первом подключении<br>
:green_circle: Наложение ограничений на многопользовательские учетные записи<br>
:green_circle: Просмотр онлайн-пользователей<br>
:green_circle: Возможность создания резервных копий пользователей и восстановления резервной копии<br>
:green_circle: Телеграм-бот <br>
:green_circle: Настройка порта входа для панели управления<br>
:green_circle: Фейковый адрес (предотвращение фильтрации) <br>
:green_circle: Ограничение IP (предотвращение входа пользователей на определенные сайты)<br>
:green_circle: Подключение API<br>
:green_circle: Мульти-сервер (скоро) <br>
:green_circle: Поворот IP <br>
:green_circle: Отправка информации об подписке на электронную почту <br>
:green_circle: Добавление ядра SING-BOX <br>

## Telegram Channel:
https://t.me/Xpanelssh

## Поддержка нас :hearts:
Ваша поддержка для нас огромное вдохновение<br> 
<p align="left">
<a href="https://plisio.net/donate/KL6W5z8k" target="_blank"><img src="https://plisio.net/img/donate/donate_light_icons_mono.png" alt="Пожертвовать криптовалюту на Plisio" width="240" height="80" /></a><br>
    
|                    TRX                   |                       ETH                         |                    Litecoin                       |
| ---------------------------------------- |:-------------------------------------------------:| -------------------------------------------------:|
| ```TYQraQ5JJXKyVD6BpTGoDYNhiLbFRfzVtV``` |  ```0x6cc08b2057EfAe4d76Af531e145DeEd4B73c9D7e``` | ```ltc1q6gq4espx74lp6jvhmr0jmxlu4al0uwemmzwdv4``` |    

</p>    

# Установка


**Необходимая операционная система**

Ubuntu 18+ (рекомендуется: Ubuntu 20)<br>

Изменение имени пользователя, пароля и порта, а также удаление XPanel с сервера (версия 3.6 и выше)

```
bash /root/xpanel.sh OR bash xpanel.sh OR xpanel
```

Для установки введите следующие команды:

**Nginx веб-сервер**
```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/install.sh --ipv4)
```

Решение проблемы отсутствия звука и изображения в приложении <br>

```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/fix-call.sh --ipv4)
```

## Включение SSL

```
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/ssl.sh --ipv4)
```

С помощью указанной выше команды можно установить SSL на панель. Обратите внимание на следующие моменты: <br>
1- Перед установкой SSL обновите панель.<br>
2- Не используйте никакие другие команды для активации SSL.<br>
3- Подключите домен или поддомен к IP-адресу сервера.<br>
4- Введите вышеуказанную команду в терминал и выполните установку SSL.
SSL устанавливается на порт, который вы определили для панели. <br>



## Звезды в течение времени

[![Звезды в течение времени](https://starchart.cc/xpanel-cp/XPanel-SSH-User-Management.svg)](https://starchart.cc/xpanel-cp/XPanel-SSH-User-Management)



