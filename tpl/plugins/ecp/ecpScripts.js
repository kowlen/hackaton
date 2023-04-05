// ПРИМЕР ДЛЯ GOOGLE CHROME
//
// Так как все функции Плагина выполняются в отдельных потоках, не заставляя браузер ждать выполнения операций, его интерфейс постороен на промисах.
// Промисы - это способ организации асинхронного кода.
// Все функции Плагина возвращают промисы, хотя текущая документация еще не содержит это.
// Универсальный метод добавления обработчиков:
// promise.then(onFulfilled, onRejected)
// onFulfilled – функция, которая будет вызвана с результатом при успешном выполнении асинхронной функции.
// onRejected – функция, которая будет вызвана с ошибкой при ошибке выполнения асинхроннной функции.
// ПРИМЕР ПОДПИСАНИЯ ДАННЫХ С ИСПОЛЬЗУЮЯ РУТОКЕН ПЛАГИН
//
// Для запуска примера в настройках Адаптера Рутокен Плагин надо поставить флажок "Разрешить открывать локальные файлы по ссылкам"
var plugin;
var pluginStatusLoaded = false;
var pluginStatusMessage;
var deviceIsActive = false;

function checkVersion(lastVersion) {
if (plugin.version.toString() < lastVersion)
console.log("download last version: ecp plugin" + lastVersion);
else
console.log("you have last version ecp plugin");
}

function getLastRtPluginVersion(callback) {
var xhr = new XMLHttpRequest();
xhr.open('GET', 'https://download.rutoken.ru/Rutoken_Plugin/Current/version.txt', true);
xhr.onreadystatechange = function() {
if (xhr.readyState == 4 && xhr.status == 200) {
var lastPluginVersion = this.response.split('Version: v.')[1].split('Release')[0].replace(/\s+/g, '');
callback(lastPluginVersion);
}
};
xhr.send();
}

window.onload = function() {
rutoken.ready
// Проверка установки расширение 'Адаптера Рутокен Плагина' в Google Chrome
.then(function() {
if (window.chrome || typeof InstallTrigger !== 'undefined') {
return rutoken.isExtensionInstalled();
} else {
return Promise.resolve(true);
}
})
// Проверка установки Рутокен Плагина
.then(function(result) {
if (result) {
return rutoken.isPluginInstalled();
} else {
return Promise.reject("Не удаётся найти расширение 'Адаптер Рутокен Плагина'");
}
})
// Загрузка плагина
.then(function(result) {
if (result) {
return rutoken.loadPlugin();
} else {
return Promise.reject("Не удаётся найти Плагин");
}
})
//Можно начинать работать с плагином
.then(function(result) {
if (!result) {
return Promise.reject("Не удаётся загрузить Плагин");
} else {
plugin = result;
return Promise.resolve();
}
})
.then(function() {
    pluginStatusLoaded = true;
    pluginStatusMessage = "Плагин загрузился";
getLastRtPluginVersion(checkVersion);
}, function(msg) {
// document.getElementById("pluginStatus").innerHTML = msg;
    pluginStatusLoaded = false;
    pluginStatusMessage = msg;
});
}

// Фунцкия обратоки ошибок от Плагина
// Загрузите нужную версию документации по ссылке https://dev.rutoken.ru/display/PUB/RutokenPluginDoc
// В разделе CLASS: ERRORCODES описаны все возможные ошибки
function handleError(reason) {
if (isNaN(reason.message)) {
alert(reason);
} else {
var errorCodes = plugin.errorCodes;
switch (parseInt(reason.message)) {
case errorCodes.PIN_INCORRECT:
alert("Неверный PIN");
break;
default:
alert("Неизвестная ошибка");
}
}
}


sign = function() {
var rutokenHandle, certHandle;
// Получение текста для подписи
var textToSign = document.getElementById("textToSign").value;
if (textToSign.length == 0) {
alert("Не хватает текста для подписи");
return;
}
// Перебор подключенных Рутокенов
plugin.enumerateDevices()
.then(function(devices) {
if (devices.length > 0) {
return Promise.resolve(devices[0]);
} else {
    deviceIsActive = true;
return Promise.reject("Рутокен не обнаружен");
}
})
// Проверка залогиненности
.then(function(firstDevice) {
rutokenHandle = firstDevice;
return plugin.getDeviceInfo(rutokenHandle, plugin.TOKEN_INFO_IS_LOGGED_IN);
})
// Логин на первый токен в списке устройств PIN-кодом по умолчанию
.then(function(isLoggedIn) {
if (isLoggedIn) {
return Promise.resolve();
} else {
return plugin.login(rutokenHandle, "12345678");
}
})
// Перебор пользовательских сертификатов на токене
.then(function() {
return plugin.enumerateCertificates(rutokenHandle, plugin.CERT_CATEGORY_USER);
})
// Подписание данных из текстового поля на первом найденом сертификате
.then(function(certs) {
if (certs.length > 0) {
certHandle = certs[0];
var options = {};
return plugin.sign(rutokenHandle, certHandle, textToSign, plugin.DATA_FORMAT_PLAIN, options);
} else {
return Promise.reject("Сертификат на Рутокен не обнаружен");
}
})
// Отображение подписанных данных в формате CMS
.then(function(cms) {
alert(cms);
})
// Закрытие сессии
.then(function() {
plugin.logout(rutokenHandle);
}, handleError);
}