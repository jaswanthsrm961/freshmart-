function addToCart(productId) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const qtyElement = document.getElementById(`cart-qty-${productId}`);
            if(qtyElement) {
                const newQty = parseInt(qtyElement.textContent) + 1;
                qtyElement.textContent = newQty;
                qtyElement.parentElement.style.display = 'flex';
            } else {
                location.reload();
            }
        }
    });
}

function decreaseQuantity(productId) {
    fetch('decrease_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const qtyElement = document.getElementById(`cart-qty-${productId}`);
            if(qtyElement) {
                const newQty = parseInt(qtyElement.textContent) - 1;
                if(newQty > 0) {
                    qtyElement.textContent = newQty;
                } else {
                    location.reload();
                }
            }
        }
    });
}

function addToCart(productId) {
  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ product_id: productId, quantity: 1 })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const qtyElement = document.querySelector(`#cart-qty-${productId}`);
      if (qtyElement) {
        const currentQty = parseInt(qtyElement.textContent);
        qtyElement.textContent = currentQty + 1;
      } else {
        const addButton = document.querySelector(`button[onclick="addToCart(${productId})"]`);
        if (addButton) {
          addButton.outerHTML = `
            <div class="quantity-controls">
              <button onclick="decreaseQuantity(${productId})">-</button>
              <span class="cart-quantity" id="cart-qty-${productId}">1</span>
              <button onclick="addToCart(${productId})">+</button>
            </div>
          `;
        }
      }
    } else {
      console.error('Error:', data.message || 'Failed to add item');
    }
  })
  .catch(error => alert('Error: ' + error));
}

function decreaseQuantity(productId) {
  fetch('decrease_cart.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ productId: productId })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const qtyElement = document.querySelector(`#cart-qty-${productId}`);
      if (qtyElement) {
        const currentQty = parseInt(qtyElement.textContent);
        if (currentQty > 1) {
          qtyElement.textContent = currentQty - 1;
        } else {
          const controlsDiv = qtyElement.closest('.quantity-controls');
          if (controlsDiv) {
            controlsDiv.outerHTML = `<button onclick="addToCart(${productId})">Add to Cart</button>`;
          }
        }
      }
    } else {
      console.error('Error:', data.message || 'Failed to update quantity');
    }
  })
  .catch(error => console.error('Error:', error));
}

function addToCart(productId) {
  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ product_id: productId, quantity: 1 })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const qtyElement = document.querySelector(`#cart-qty-${productId}`);
      if (qtyElement) {
        const currentQty = parseInt(qtyElement.textContent);
        qtyElement.textContent = currentQty + 1;
      } else {
        const addButton = document.querySelector(`button[onclick="addToCart(${productId})"]`);
        if (addButton) {
          addButton.outerHTML = `
            <div class="quantity-controls">
              <button onclick="decreaseQuantity(${productId})">-</button>
              <span class="cart-quantity" id="cart-qty-${productId}">1</span>
              <button onclick="addToCart(${productId})">+</button>
            </div>
          `;
        }
      }
    } else {
      console.error('Error:', data.message || 'Failed to add item');
    }
  })
  .catch(error => console.error('Error:', error));
}