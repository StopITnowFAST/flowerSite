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

    // Инициализация начального значения общего итога
    updateGrandTotal();
});
