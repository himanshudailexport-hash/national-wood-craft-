// Simple JS Filter for product category
const buttons = document.querySelectorAll(".category-btn");
const products = document.querySelectorAll(".product-card");

buttons.forEach((btn) => {
  btn.addEventListener("click", () => {
    buttons.forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");
    const category = btn.dataset.category;

    products.forEach((prod) => {
      if (category === "all" || prod.dataset.category === category) {
        prod.style.display = "block";
      } else {
        prod.style.display = "none";
      }
    });
  });
});

// product-details image slids

const mainImage = document.querySelector(".main-image");
const thumbs = document.querySelectorAll(".gallery-thumb");
thumbs.forEach((thumb) => {
  thumb.addEventListener("click", function () {
    mainImage.src = this.src;
  });
});



// cart function 

function loadCart() {
  return JSON.parse(localStorage.getItem("cartItems")) || [];
}

function saveCart(cart) {
  localStorage.setItem("cartItems", JSON.stringify(cart));
  window.dispatchEvent(new Event("cart-updated")); // Update cart badge
}

function renderCart() {
  let cart = loadCart();
  let container = document.getElementById("cart-items");

  if (cart.length === 0) {
    container.innerHTML = `<p class="text-center text-muted">Your cart is empty</p>`;
    document.getElementById("subtotal").innerText = "₹0";
    document.getElementById("total").innerText = "₹0";
    return;
  }

  let html = "";
  let subtotal = 0;

  cart.forEach((item, index) => {
    let total = item.price * item.qty;
    subtotal += total;

    html += `
      <div class="cart-row mb-4 pb-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
          
          <div class="d-flex align-items-center mb-3 mb-md-0">
              <img src="${item.image}" class="cart-img rounded-3 me-3">
              <div>
                  <h5 class="fw-semibold mb-1 text-wood-brown">${item.name}</h5>
                  <p class="text-muted small">${item.category}</p>
              </div>
          </div>

          <div class="d-flex align-items-center">
              <button class="qty-btn" onclick="changeQty(${index}, -1)">−</button>
              <span class="qty-display">${item.qty}</span>
              <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>

              <h6 class="fw-bold mb-0 ms-3 text-forest">₹${total}</h6>

              <button class="btn btn-link text-danger ms-3 p-0" onclick="removeItem(${index})">
                  Remove
              </button>
          </div>
      </div>
    `;
  });

  container.innerHTML = html;

  document.getElementById("subtotal").innerText = "₹" + subtotal;
  document.getElementById("total").innerText = "₹" + (subtotal + 99);
}

function changeQty(index, step) {
  let cart = loadCart();
  cart[index].qty += step;

  if (cart[index].qty < 1) cart[index].qty = 1;

  saveCart(cart);
  renderCart();
}

function removeItem(index) {
  let cart = loadCart();
  cart.splice(index, 1);
  saveCart(cart);
  renderCart();
}

document.addEventListener("DOMContentLoaded", renderCart);
