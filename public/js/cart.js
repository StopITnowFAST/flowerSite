document.addEventListener('DOMContentLoaded', () => {
    // Обновление общей стоимости
    const updateGrandTotal = () => {
        let grandTotal = 0;
        document.querySelectorAll('.total').forEach(totalCell => {
            grandTotal += parseFloat(totalCell.textContent);
        });
        document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
    };

    // Добавляем слушатели на изменения количества
    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('input', function () {
            const row = this.closest('tr');
            const price = parseFloat(this.dataset.price);
            const quantity = parseInt(this.value) || 1; // Если пустое поле, подставляем 1
            const totalCell = row.querySelector('.total');

            // Обновляем стоимость для данной строки
            totalCell.textContent = (price * quantity).toFixed(2);

            // Обновляем общий итог
            updateGrandTotal();
        });
    });

    // Удаление товара из корзины
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function () {
            const flowerId = this.dataset.id;

            // Удаляем строку из таблицы
            const row = document.querySelector(`tr[data-flower-id="${flowerId}"]`);
            if (row) {
                row.remove();
            }

            // Обновляем общий итог
            updateGrandTotal();

            // Можно отправить запрос на сервер для удаления из БД
            fetch(`/cart/remove/${flowerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ flowerId }),
            }).catch(error => {
                console.error('Error removing item:', error);
            });
        });
    });

    // Инициализация начального значения общего итога
    updateGrandTotal();
});
