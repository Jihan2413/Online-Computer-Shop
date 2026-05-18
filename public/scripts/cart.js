function addToCart(productId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

    const apiUrl = (window.APP_BASE_URL || '') + '/api/cart_add.php';
    fetch(apiUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check"></i> Added!';
            btn.style.background = '#28a745';
            const badge = document.getElementById('cart-count');
            if (badge && data.cart_count !== undefined) {
                badge.textContent = data.cart_count;
                badge.style.display = 'inline';
            }
            setTimeout(function () {
                btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
        } else {
            btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
            btn.disabled = false;
            alert(data.message || 'Could not add to cart.');
        }
    })
    .catch(function () {
        btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
        btn.disabled = false;
        alert('Network error. Please try again.');
    });
}
