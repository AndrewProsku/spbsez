Requests (bitrix module)
====================
Модуль для сохранения и отправки на email заявок на обратный звонок, бронирование.

Для подключения модуля добавьте в composer.json

```
{
     "require": {
         "kelnik/module-requests": "^1.0"
     },
     "repositories": [
         {
             "type": "git",
             "url":  "git@gitlab.kelnik.pro:estate/kelnik.module.requests.git"
         }
     ]
 }
 ```
 
 При установке модуля появляются настройки для заполнения электронных адресов, куда должны отправляться заявки:
 Настройки -> Настройки продукта -> Настройки модулей -> Заявки с форм.
 
 Также при установке добавляются новые почтовые события (callback_request, booking_request) и шаблоны.
 
 Запросы отправляются по url **/ajax/request.php** через ajax.