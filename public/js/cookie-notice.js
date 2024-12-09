   // Проверяем наличие куки
   if (!document.cookie.split('; ').find(row => row.startsWith('cookie_accepted='))) {
    document.getElementById('cookieNotice').style.display = 'block'; // Показываем уведомление
}

function acceptCookieNotice() {
    document.getElementById('cookieNotice').style.display = 'none';
    document.cookie = "cookie_accepted=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
}
