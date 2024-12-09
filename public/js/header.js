document.addEventListener('DOMContentLoaded', function() {
    fetch('/cities')
        .then(response => response.json())
        .then(data => {
            const citySelect = document.getElementById('city');
            data.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });

            // Получаем значение города из cookie
            const savedCity = getCookie('selectedCity');

            // Устанавливаем выбранное значение из cookie или URL
            const urlParams = new URLSearchParams(window.location.search);
            const selectedCity = urlParams.get('city') || savedCity;

            if (selectedCity) {
                citySelect.value = selectedCity;
            }

            // Проверяем, если находимся на странице, где не нужно отображать город в URL
            const pagesWithoutCityParam = [
                'http://localhost:8000/chats',
                'http://localhost:8000/adverts/create',
                'http://localhost:8000/my-adverts'
            ];

            // Если текущая страница не в списке исключений, добавляем город в URL
            if (!pagesWithoutCityParam.includes(window.location.origin + window.location.pathname)) {
                updateUrlWithCity(selectedCity);
            }
        })
        .catch(error => console.error('Ошибка при загрузке городов:', error));
});

function updateCitySelection() {
    const selectedCity = document.getElementById('city').value;

    // Сохраняем выбранный город в cookie
    setCookie('selectedCity', selectedCity, 7); // Сохраняем на 7 дней

    // Обновляем URL с выбранным городом
    updateUrlWithCity(selectedCity);

    // Обновляем страницу
    location.reload();
}

function updateUrlWithCity(city) {
    const baseUrl = window.location.origin + window.location.pathname; // Получаем базовый URL
    const currentUrlParams = new URLSearchParams(window.location.search);

    // Удаляем параметр city, если он не нужен
    if (city) {
        currentUrlParams.set('city', city);
    } else {
        currentUrlParams.delete('city');
    }

    // Определяем страницы, где не нужно отображать параметр city
    const pagesWithoutCityParam = [
        'http://localhost:8000/chats',
        'http://localhost:8000/adverts/create',
        'http://localhost:8000/my-adverts'
    ];

    // Если текущая страница не в списке исключений, обновляем URL
    if (!pagesWithoutCityParam.includes(baseUrl)) {
        const newUrl = baseUrl + '?' + currentUrlParams.toString();
        window.history.replaceState({}, '', newUrl); // Обновляем URL без перезагрузки страницы
    }
}

// Функция для установки cookie
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Функция для получения cookie
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}