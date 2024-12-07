const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
addToCartBtns.forEach(button => {
    button.addEventListener('click', function() {
        const flowerId = button.getAttribute('data-flower-id');
        const flowerPrice = button.getAttribute('data-flower-price');
        const popup = document.getElementById('addToCartPopup');
        popup.style.display = 'flex';
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('totalPrice');
        totalPriceElement.textContent = (flowerPrice * quantityInput.value).toFixed(2);
        quantityInput.addEventListener('input', function() {
            totalPriceElement.textContent = (flowerPrice * quantityInput.value).toFixed(2);
        });
        document.getElementById('cartForm').addEventListener('submit', function(event) {
            event.preventDefault();
            popup.style.display = 'none'; 
        });
    });
});
document.getElementById('closePopupBtn').addEventListener('click', function() {
    document.getElementById('addToCartPopup').style.display = 'none';
});
