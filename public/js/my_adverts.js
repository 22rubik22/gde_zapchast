function openEditModal(id) {
    var row = document.querySelector(`tr[data-id-info="${id}"]`);

    document.getElementById("editAdvertId").value = id;
    document.getElementById("art_number").value = row.getAttribute('data-art-number');
    document.getElementById("product_name").value = row.getAttribute('data-product-name');
    document.getElementById("number").value = row.getAttribute('data-number-info');
    document.getElementById("new_used").value = row.getAttribute('data-new-used');
    document.getElementById("brand").value = row.getAttribute('data-brand-info');
    document.getElementById("model").value = row.getAttribute('data-model-info');
    document.getElementById("year").value = row.getAttribute('data-year');
    document.getElementById("body").value = row.getAttribute('data-body-info');
    document.getElementById("engine").value = row.getAttribute('data-engine-info');
    document.getElementById("L_R").value = row.getAttribute('data-L_R');
    document.getElementById("F_R").value = row.getAttribute('data-F_R');
    document.getElementById("U_D").value = row.getAttribute('data-U_D');
    document.getElementById("color").value = row.getAttribute('data-color');
    document.getElementById("applicability").value = row.getAttribute('data-applicability');
    document.getElementById("quantity").value = row.getAttribute('data-quantity');
    document.getElementById("price").value = row.getAttribute('data-price-info');
    document.getElementById("availability").value = row.getAttribute('data-availability');
    document.getElementById("main_photo_url").value = row.getAttribute('data-main-photo-url');
    document.getElementById("additional_photo_url_1").value = row.getAttribute('data-additional-photo-url-1');
    document.getElementById("additional_photo_url_2").value = row.getAttribute('data-additional-photo-url-2');
    document.getElementById("additional_photo_url_3").value = row.getAttribute('data-additional-photo-url-3');

    // Заполнение скрытых полей старыми значениями
    document.getElementById("old_art_number").value = row.getAttribute('data-art-number');
    document.getElementById("old_product_name").value = row.getAttribute('data-product-name');
    document.getElementById("old_number").value = row.getAttribute('data-number-info');
    document.getElementById("old_new_used").value = row.getAttribute('data-new-used');
    document.getElementById("old_brand").value = row.getAttribute('data-brand-info');
    document.getElementById("old_model").value = row.getAttribute('data-model-info');
    document.getElementById("old_year").value = row.getAttribute('data-year');
    document.getElementById("old_body").value = row.getAttribute('data-body-info');
    document.getElementById("old_engine").value = row.getAttribute('data-engine-info');
    document.getElementById("old_L_R").value = row.getAttribute('data-L_R');
    document.getElementById("old_F_R").value = row.getAttribute('data-F_R');
    document.getElementById("old_U_D").value = row.getAttribute('data-U_D');
    document.getElementById("old_color").value = row.getAttribute('data-color');
    document.getElementById("old_applicability").value = row.getAttribute('data-applicability');
    document.getElementById("old_quantity").value = row.getAttribute('data-quantity');
    document.getElementById("old_price").value = row.getAttribute('data-price-info');
    document.getElementById("old_availability").value = row.getAttribute('data-availability');
    document.getElementById("old_main_photo_url").value = row.getAttribute('data-main-photo-url');
    document.getElementById("old_additional_photo_url_1").value = row.getAttribute('data-additional-photo-url-1');
    document.getElementById("old_additional_photo_url_2").value = row.getAttribute('data-additional-photo-url-2');
    document.getElementById("old_additional_photo_url_3").value = row.getAttribute('data-additional-photo-url-3');

    // Показываем модальное окно
    var editModal = document.getElementById("editModal");
    editModal.style.display = "block";

    // Закрытие модального окна при нажатии на элемент "close"
    var editSpan = editModal.getElementsByClassName("close")[0];
    editSpan.onclick = function() {
        editModal.style.display = "none";
    }

    // Закрытие модального окна при клике вне его области
    editModal.onclick = function(event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }
}
    // Получаем все кнопки редактирования
    var editButtons = document.getElementsByClassName("edit-btn");

    // Перебираем все кнопки редактирования и добавляем обработчик события для каждой кнопки
    for (var i = 0; i < editButtons.length; i++) {
        editButtons[i].addEventListener("click", function() {
            var id = this.getAttribute('data-id');
            openEditModal(id);
        });
    }

    // Функция для открытия модального окна просмотра
    function openViewModal(id) {
        var row = document.querySelector(`tr[data-id-info="${id}"]`);

        var artNumber = row.getAttribute('data-art-number');
        var productName = row.getAttribute('data-product-name');
        var brandInfo = row.getAttribute('data-brand-info');
        var modelInfo = row.getAttribute('data-model-info');
        var bodyInfo = row.getAttribute('data-body-info');
        var numberInfo = row.getAttribute('data-number-info');
        var engineInfo = row.getAttribute('data-engine-info');
        var mainPhotoUrl = row.getAttribute('data-main-photo-url') || '/static/not_found.jpg'; // Путь к изображению "Image Not Found"
        var additionalPhotoUrl1 = row.getAttribute('data-additional-photo-url-1') || '/static/not_found.jpg';
        var additionalPhotoUrl2 = row.getAttribute('data-additional-photo-url-2') || '/static/not_found.jpg';
        var additionalPhotoUrl3 = row.getAttribute('data-additional-photo-url-3') || '/static/not_found.jpg';
        var priceInfo = row.getAttribute('data-price-info');

        // Заполняем модальное окно данными товара
        document.getElementById("modalMainImg").src = mainPhotoUrl;
        document.getElementById("modalAdditionalImg1").src = additionalPhotoUrl1;
        document.getElementById("modalAdditionalImg2").src = additionalPhotoUrl2;
        document.getElementById("modalAdditionalImg3").src = additionalPhotoUrl3;
        document.getElementById("modalInfo").innerHTML = `
            Артикул: ${artNumber}<br>
            Наименование: ${productName}<br>
            Марка: ${brandInfo}<br>
            Модель: ${modelInfo}<br>
            Кузов: ${bodyInfo}<br>
            Двигатель: ${engineInfo}<br>
            Номер: ${numberInfo}<br>
            Цена: ${priceInfo}
        `;

        // Показываем модальное окно
        var viewModal = document.getElementById("viewModal");
        viewModal.style.display = "block";

        // Закрытие модального окна при нажатии на элемент "close"
        var viewSpan = viewModal.getElementsByClassName("close")[0];
        viewSpan.onclick = function() {
            viewModal.style.display = "none";
        }

        // Закрытие модального окна при клике вне его области
        viewModal.onclick = function(event) {
            if (event.target == viewModal) {
                viewModal.style.display = "none";
            }
        }
    }

    // Получаем все строки таблицы
    var tableRows = document.getElementsByTagName("tr");

    // Перебираем все строки таблицы и добавляем обработчик события для каждой строки
    for (var i = 1; i < tableRows.length; i++) { // Начинаем с 1, чтобы пропустить заголовок
        tableRows[i].addEventListener("click", function() {
            var idNumber = this.getAttribute('data-id-info');
            openViewModal(idNumber);
        });
    }