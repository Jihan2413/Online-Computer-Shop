
function getRootPath() {
    var path = window.location.pathname;
    var root = path.substring(0, path.lastIndexOf('/') + 1);

    if (root.indexOf('/views/') !== -1) {
        root = root.replace('/views/', '/');
    }
    return root;
}

var ROOT = getRootPath();

function doSearch() {
    var q = document.getElementById('searchInput').value.trim();

    if (q.length < 2) {
        alert('Please enter at least 2 characters to search.');
        return;
    }

    var xhttp = new XMLHttpRequest();
    xhttp.open('GET', ROOT + 'api/products/search.php?q=' + encodeURIComponent(q), true);

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);
            renderSearchResults(data, q);
        }
    };

    xhttp.send();
}

document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('searchInput');
    if (input) {
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') doSearch();
        });
    }
});

function renderSearchResults(data, q) {
    var overlay  = document.getElementById('searchOverlay');
    var resultsDiv = document.getElementById('searchResults');

    overlay.style.display = 'block';

    if (!data.success || data.products.length === 0) {
        resultsDiv.innerHTML = '<p class="empty-msg">No products found for "<strong>' + escHtml(q) + '</strong>".</p>';
        return;
    }

    var html = '';
    data.products.forEach(function (p) {
        html += buildProductCard(p);
    });
    resultsDiv.innerHTML = html;
}

function closeSearch() {
    document.getElementById('searchOverlay').style.display = 'none';
    document.getElementById('searchResults').innerHTML = '';
    document.getElementById('searchInput').value = '';
}

function doFilter() {
    var minPrice   = document.getElementById('minPrice')      ? document.getElementById('minPrice').value   : '';
    var maxPrice   = document.getElementById('maxPrice')      ? document.getElementById('maxPrice').value   : '';
    var categoryEl = document.getElementById('filterCategory');
    var brandEl    = document.getElementById('filterBrand');

    var categoryId = categoryEl ? categoryEl.value : '';
    var brandId    = brandEl    ? brandEl.value    : '';

    var filterMsg = document.getElementById('filterMsg');

    if (minPrice !== '' && maxPrice !== '' && parseFloat(minPrice) > parseFloat(maxPrice)) {
        showMsg(filterMsg, 'Min price cannot be greater than max price.', 'error');
        return;
    }

    var params = [];
    if (minPrice !== '')   
        params.push('min=' + encodeURIComponent(minPrice));
    if (maxPrice !== '')   
        params.push('max=' + encodeURIComponent(maxPrice));
    if (categoryId !== '') 
        params.push('category_id=' + encodeURIComponent(categoryId));
    if (brandId !== '')    
        params.push('brand_id='    + encodeURIComponent(brandId));

    var url = ROOT + 'api/products/filter.php?' + params.join('&');

    showMsg(filterMsg, 'Filtering…', '');

    var xhttp = new XMLHttpRequest();
    xhttp.open('GET', url, true);

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);

            if (!data.success) {
                showMsg(filterMsg, data.message, 'error');
                return;
            }

            renderProductGrid(data.products);
            showMsg(filterMsg, data.count + ' product(s) found.', 'success');
        }
    };

    xhttp.send();
}

function resetFilter() {
    var grid = document.getElementById('productGrid');
    if (grid) {
        window.location.reload();
    }
}

function renderProductGrid(products) {
    var grid      = document.getElementById('productGrid');
    var countSpan = document.getElementById('productCount');

    if (!grid) return;

    if (products.length === 0) {
        grid.innerHTML = '<p class="empty-msg">No products match your filter.</p>';
        if (countSpan) countSpan.textContent = '(0)';
        return;
    }

    var html = '';
    products.forEach(function (p) {
        html += buildProductCard(p);
    });

    grid.innerHTML = html;
    if (countSpan) countSpan.textContent = '(' + products.length + ')';
}


function buildProductCard(p) {
    var imgSrc   = ROOT + 'public/uploads/' + escHtml(p.image);
    var detailLink = ROOT + 'product.php?id=' + p.id;
    var inStock = p.stock > 0;
    var stockText  = inStock ? 'In Stock' : 'Out of Stock';
    var stockClass = inStock ? 'in-stock' : 'out-stock';
    var review  = p.manufacturer_review.substring(0, 80) + '…';

    return '<div class="product-card">' +
        '<a href="' + detailLink + '">' +
            '<img src="' + imgSrc + '" alt="' + escHtml(p.name) + '" onerror="this.src=\'' + ROOT + 'public/assets/css/no-image.png\'">' +
            '<h3>' + escHtml(p.name) + '</h3>' +
        '</a>' +
        '<p class="review-snippet">' + escHtml(review) + '</p>' +
        '<p class="price">৳ ' + parseFloat(p.price).toLocaleString('en-BD', {minimumFractionDigits:2}) + '</p>' +
        '<p class="stock ' + stockClass + '">' + stockText + '</p>' +
        '<a href="' + detailLink + '" class="btn-secondary">View Details</a>' +
    '</div>';
}

function addToCart(productId, btnEl) {
    var qtyInput = document.getElementById('cartQty');
    var qty      = qtyInput ? parseInt(qtyInput.value, 10) : 1;
    var msgEl    = document.getElementById('cartMsg');

    if (isNaN(qty) || qty < 1) {
        showMsg(msgEl, 'Please enter a valid quantity (minimum 1).', 'error');
        return;
    }

    btnEl.disabled = true;
    btnEl.textContent = 'Adding…';

    var xhttp = new XMLHttpRequest();
    xhttp.open('POST', ROOT + 'api/cart/add.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);

            if (data.redirect) {
                window.location.href = ROOT + 'views/login.php';
                return;
            }

            if (data.success) {
                showMsg(msgEl, '✔ ' + data.message, 'success');
                updateCartBadge(data.item_count);
            } else {
                showMsg(msgEl, '✘ ' + data.message, 'error');
            }

            btnEl.disabled = false;
            btnEl.textContent = '🛒 Add to Cart';
        }
    };

    xhttp.send('product_id=' + productId + '&quantity=' + qty);
}

function updateCart(cartId) {
    var qtyInput = document.getElementById('qty-' + cartId);
    var qty= parseInt(qtyInput.value, 10);
    var msgEl = document.getElementById('msg-' + cartId);

    if (isNaN(qty) || qty < 1) {
        showMsg(msgEl, 'Quantity must be at least 1.', 'error');
        qtyInput.value = 1;
        return;
    }

    var xhttp = new XMLHttpRequest();
    xhttp.open('POST', ROOT + 'api/cart/update.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);

            if (data.success) {
                var subtotalCell = document.getElementById('subtotal-' + cartId);
                if (subtotalCell) subtotalCell.textContent = '৳ ' + data.subtotal;

                updateCartTotal(data.item_count, data.total);
                showMsg(msgEl, '✔ Updated', 'success');
            } else {
                showMsg(msgEl, '✘ ' + data.message, 'error');
                if (data.message.indexOf('available') !== -1) {
                    var max = parseInt(qtyInput.max, 10);
                    qtyInput.value = max;
                }
            }
        }
    };

    xhttp.send('cart_id=' + cartId + '&quantity=' + qty);
}

function changeQty(cartId, delta) {
    var input = document.getElementById('qty-' + cartId);
    var current = parseInt(input.value, 10) || 1;
    var next= current + delta;
    var min = parseInt(input.min, 10) || 1;
    var max= parseInt(input.max, 10) || 9999;

    if (next < min) next = min;
    if (next > max) next = max;

    input.value = next;
    updateCart(cartId);
}

function removeItem(cartId) {
    if (!confirm('Remove this item from your cart?')) return;

    var xhttp = new XMLHttpRequest();
    xhttp.open('POST', ROOT + 'api/cart/remove.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);

            if (data.success) {
                // Remove the row from the table
                var row = document.getElementById('cart-row-' + cartId);
                if (row) row.remove();

                updateCartTotal(data.item_count, data.total);

                // If cart is now empty show empty message
                if (data.item_count === 0) {
                    var tableEl = document.getElementById('cartTable');
                    if (tableEl) tableEl.style.display = 'none';

                    var mainEl = document.querySelector('.cart-main');
                    if (mainEl) {
                        var p = document.createElement('p');
                        p.className = 'empty-msg';
                        p.innerHTML = 'Your cart is empty. <a href="' + ROOT + 'index.php">Continue shopping →</a>';
                        mainEl.appendChild(p);
                    }

                    var footer = document.querySelector('.cart-footer');
                    if (footer) footer.style.display = 'none';
                }

                updateCartBadge(data.item_count);
            }
        }
    };

    xhttp.send('cart_id=' + cartId);
}


function updateCartTotal(itemCount, total) {
    var totalEl = document.getElementById('cartTotal');
    if (totalEl) {
        totalEl.innerHTML = '<span>Total (' + itemCount + ' items):</span>' +
                            '<strong>৳ ' + total + '</strong>';
    }
}

function updateCartBadge(count) {
    var badge = document.querySelector('.cart-badge');

    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            var cartLink = document.querySelector('.cart-link');
            if (cartLink) {
                var span = document.createElement('span');
                span.className   = 'cart-badge';
                span.textContent = count;
                cartLink.appendChild(span);
            }
        }
    } else {
        if (badge) badge.remove();
    }
}

function showMsg(el, text, type) {
    if (!el) return;
    el.textContent  = text;
    el.className    = 'msg ' + type;
    el.style.display = 'block';

    if (type === 'success') {
        setTimeout(function () {
            el.style.display = 'none';
        }, 3000);
    }
}

function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;')
        .replace(/'/g,  '&#039;');
}
