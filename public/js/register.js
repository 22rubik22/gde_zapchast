// Инициализация карты и автозаполнение адреса
function initMap() {
    const suggestView = new ymaps.SuggestView('addressInput');
}

$(document).ready(function() {
    // Инициализация карты после загрузки страницы
    ymaps.ready(initMap);
});

function togglePasswordVisibility(passwordId, confirmPasswordId, element) {
    const passwordInput = document.getElementById(passwordId);
    const confirmPasswordInput = document.getElementById(confirmPasswordId);
    const icon = element.querySelector('.password-icon');

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        confirmPasswordInput.type = "text";
        icon.src = "images/open_password.png"; // Меняем изображение на "open_password.png"
    } else {
        passwordInput.type = "password";
        confirmPasswordInput.type = "password";
        icon.src = "images/close_password.png"; // Меняем изображение на "close_password.png"
    }
}

// Подсказка города 
$(document).ready(function() {
    $('#cityInput').on('input', function() {
        const query = $(this).val();
        
        if (query.length > 2) { // Начинаем поиск после ввода 3 символов
            $.ajax({
                url: '/cities/search', // URL для вашего маршрута
                method: 'GET',
                data: { q: query },
                success: function(data) {
                    displaySuggestions(data);
                }
            });
        } else {
            $('#citySuggestions').empty().hide();
        }
    });

    function displaySuggestions(cities) {
        const suggestionsContainer = $('#citySuggestions');
        suggestionsContainer.empty();

        if (cities.length > 0) {
            cities.forEach(city => {
                const suggestionItem = $('<div>')
                    .text(city.city)
                    .on('click', function() {
                        $('#cityInput').val(city.city);
                        suggestionsContainer.empty().hide();
                    });
                suggestionsContainer.append(suggestionItem);
            });
            suggestionsContainer.show();
        } else {
            suggestionsContainer.hide();
        }
    }
});


// Форматирование номера телефона 7 (XXX) X XXX-XXX

$(document).ready(function() {
    $('#phoneInput').on('input', function() {
        let value = $(this).val().replace(/\D/g, ''); // Удаляем все нецифровые символы

        // Заменяем 8 на 7 и обрабатываем другие префиксы
        if (value.startsWith('8')) {
            value = '7' + value.slice(1);
        } else if (value.startsWith('+7')) {
            value = '7' + value.slice(2);
        } else if (value.startsWith('9')) {
            value = '7' + value;
        }

        if (value.length > 11) {
            value = value.slice(0, 11); // Ограничиваем длину до 11 символов
        }

        // Форматируем номер в реальном времени
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue += value[0]; // Код страны
        }
        if (value.length > 1) {
            formattedValue += ' (' + value.slice(1, 4); // Скобки и первые три цифры
        }
        if (value.length >= 4) {
            formattedValue += ') ' + value.slice(4, 5); // Закрывающая скобка и следующая цифра
        }
        if (value.length >= 5) {
            formattedValue += ' ' + value.slice(5, 8); // Пробел и следующие три цифры
        }
        if (value.length >= 8) {
            formattedValue += ' - ' + value.slice(8, 11); // Тире и следующие две цифры
        }
       

        $(this).val(formattedValue); // Обновляем значение поля ввода
    });

    $('#phoneInput').on('keydown', function(e) {
        // Если нажата клавиша Backspace или Delete, удаляем последний символ
        if (e.key === "Backspace" || e.key === "Delete") {
            let currentValue = $(this).val();
            let newValue = currentValue.replace(/\D/g, ''); // Удаляем все нецифровые символы

            // Удаляем последний символ
            newValue = newValue.slice(0, -1);
            
            // Обновляем значение поля ввода
            $(this).val(formatPhoneNumber(newValue));
            e.preventDefault(); // Отменяем стандартное поведение
        }
    });

    function formatPhoneNumber(value) {
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue += value[0]; // Код страны
        }
        if (value.length > 1) {
            formattedValue += ' (' + value.slice(1, 4); // Скобки и первые три цифры
        }
        if (value.length >= 4) {
            formattedValue += ') ' + value.slice(4, 5); // Закрывающая скобка и следующая цифра
        }
        if (value.length >= 5) {
            formattedValue += ' ' + value.slice(5, 8); // Пробел и следующие три цифры
        }
        if (value.length >= 8) {
            formattedValue += ' - ' + value.slice(8, 11); // Тире и следующие две цифры
        }
        
        
        return formattedValue;
    }
});

// согласие с офертой
$(document).ready(function() {
    $('#registerForm').on('submit', function(e) {
        if (!$('#agree').is(':checked')) {
            e.preventDefault(); // Отменяем отправку формы
            $('#agreementError').show(); // Показываем сообщение об ошибке
        } else {
            $('#agreementError').hide(); // Скрываем сообщение об ошибке
        }
    });
});

