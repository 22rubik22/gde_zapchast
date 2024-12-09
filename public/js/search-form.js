$(document).ready(function() {
    // Получаем URL для получения списка марок
    var brandsUrl = $('#main-form').data('brands-url');

    // Получаем список марок с сервера
    $.getJSON(brandsUrl, function(data) {
        // Настраиваем автозаполнение для поля ввода марки
        $('#brand-input').autocomplete({
            source: data,
            minLength: 2,
            select: function(event, ui) {
                // При выборе марки из подсказок, устанавливаем значение в скрытое поле
                $('#brand').val(ui.item.value);
            }
        });
    });

    // При изменении поля ввода марки, обновляем скрытое поле
    $('#brand-input').on('input', function() {
        $('#brand').val($(this).val());
    });
        // При изменении выбора марки из списка, обновляем скрытое поле и поле ввода
        $('#brand-select').on('change', function() {
            var selectedBrand = $(this).val();
            $('#brand-input').val(selectedBrand); // Устанавливаем значение в поле ввода
            $('#brand').val(selectedBrand); // Устанавливаем значение в скрытое поле
        });
    
        $('#show-button').on('click', function() {
            // Собираем данные из основной формы
            var formData = $('#main-form').serialize();
            
            // Добавляем данные из модификаций, если они есть
            var modifications = $('#modifications input:checked').map(function() {
                return $(this).val();
            }).get().join(',');
    
            if (modifications) {
                formData += '&modifications=' + encodeURIComponent(modifications);
            }
        });


    // Проверка наличия параметров в URL
    function checkUrlParameters() {
        const urlParams = new URLSearchParams(window.location.search);
        const brand = urlParams.get('brand');
        const model = urlParams.get('model');
        const year = urlParams.get('year');
        const city = urlParams.get('city'); // Если у вас есть параметр city

        // Если параметры присутствуют, показываем модификации
        if (brand && model && year) {
            $('#modifications').show();
            $('#year').val(year); // Устанавливаем значение года в поле формы
        } else {
            $('#modifications').hide(); // Скрываем элемент с модификациями, если параметры отсутствуют
        }

        // Если нужно, установить город в соответствующее поле
        if (city) {
            $('#city').val(city); // Устанавливаем значение города в поле формы
        }
    }

    // Вызов функции для проверки параметров при загрузке страницы
    checkUrlParameters();

    // Проверка и отображение сохраненных модификаций из куки при загрузке страницы
    function loadSavedModifications() {
        var selectedModifications = Cookies.get('selectedModifications');
        if (selectedModifications) {
            selectedModifications = JSON.parse(selectedModifications);
            $.each(selectedModifications, function(index, modification) {
                $('#modifications').append('<div><input type="checkbox" class="modification-checkbox" value="' + modification.id_modification + '" checked>' + modification.modification + '</div>');
            });
        }
    }

    // Вызов функции для загрузки сохраненных модификаций при загрузке страницы
    loadSavedModifications();

    $('#brand').change(function() {
        var brand = $(this).val();
        var url = $(this).data('url');

        // Очистка списка моделей и годов
        $('#model').empty().append('<option value="">Выберите модель</option>');
        $('#year').empty().append('<option value="">Выберите год выпуска</option>');
        $('#modifications').empty(); // Очистка модификаций

        if (brand) {
            $.ajax({
                url: url,
                type: 'GET',
                data: { brand: brand },
                success: function(data) {
                    // Заполнение списка моделей
                    $.each(data, function(index, model) {
                        $('#model').append('<option value="' + model + '">' + model + '</option>');
                    });
                }
            });
        }
    });

    $('#model').change(function() {
        var brand = $('#brand').val();
        var model = $(this).val();
        
        // Очистка списка годов и модификаций
        $('#year').empty().append('<option value="">Выберите год выпуска</option>');
        $('#modifications').empty(); // Очистка модификаций

        if (brand && model) {
            $.ajax({
                url: '/get-years', // Создайте маршрут для получения годов
                type: 'GET',
                data: { brand: brand, model: model },
                success: function(data) {
                    // Заполнение списка годов
                    $.each(data, function(index, year) {
                        $('#year').append('<option value="' + year + '">' + year + '</option>');
                    });
                }
            });
        }
    });

    $('#year').change(function() {
        var brand = $('#brand').val();
        var model = $('#model').val();
        var year = $(this).val();

        // Очистка модификаций
        $('#modifications').empty();

        if (brand && model && year) {
            $('#modifications').show(); // Показываем модификации, если все параметры выбраны

            $.ajax({
                url: '/get-modifications', // Создайте маршрут для получения модификаций
                type: 'GET',
                data: { brand: brand, model: model, year: year },
                success: function(data) {
                    // Заполнение списка модификаций и сохранение в куки
                    $.each(data, function(index, modification) {
                        $('#modifications').append('<div><input type="checkbox" class="modification-checkbox" value="' + modification.id_modification + '" checked>' + modification.modification + '</div>');
                    });

                    // Сохранение состояния чекбоксов в куки при изменении
                    $('.modification-checkbox').change(function() {
                        saveSelectedModifications();
                    });
                    
                    // Сохранение состояния в куки по умолчанию
                    saveSelectedModifications();
                }
            });
        }
    });

    function saveSelectedModifications() {
        var selectedModifications = [];
        $('.modification-checkbox:checked').each(function() {
            var modificationId = $(this).val();
            var modificationText = $(this).parent().text().trim(); // Получаем текст модификации
            selectedModifications.push({
                id_modification: modificationId,
                modification: modificationText
            });
        });
        Cookies.set('selectedModifications', JSON.stringify(selectedModifications), { expires: 7 }); // Сохраняем на 7 дней
    }
});

function scrollToForm() {
    document.getElementById('create-product-form').scrollIntoView({ behavior: 'smooth' });
}