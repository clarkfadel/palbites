document.addEventListener("DOMContentLoaded", function () {
    const cartCounter = document.querySelector(".cart-counter");

    function fetchCart() {
        fetch("cart_handler.php?action=getCart")
            .then(response => response.json())
            .then(cart => {
                updateCartCounter(cart.length);
            })
            .catch(error => console.error("Error fetching cart:", error));
    }

    function updateCartCounter(count) {
        cartCounter.textContent = count;
        cartCounter.classList.add("show");
    }

    function addToCart(productName, productPrice, productImage, quantity = 1) {
        fetch("cart_handler.php?action=add", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name: productName, price: productPrice, image: productImage, quantity })
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            fetchCart();
        })
        .catch(error => console.error("Error adding to cart:", error));
    }

    // Adding from products.php
    document.querySelectorAll(".products-content h6").forEach(button => {
        button.addEventListener("click", function () {
            const productElement = this.closest(".products-content");
            const productName = productElement.querySelector("a").textContent.trim();
            const productPrice = parseFloat(productElement.querySelector("h4").textContent.trim().replace("₱", ""));
            const productImage = productElement.querySelector("img").src;

            addToCart(productName, productPrice, productImage);
        });
    });

    // Adding from product-view.php
    const productViewButton = document.querySelector(".product-button h4");
    if (productViewButton) {
        productViewButton.addEventListener("click", function () {
            const productElement = document.querySelector(".product-content");
            const productName = productElement.querySelector("h1").textContent.trim();
            const productPrice = parseFloat(productElement.querySelector("h2 span + text").textContent.trim());
            const productImage = document.querySelector(".product-img img").src;
            const quantity = parseInt(document.querySelector(".product-quantity input").value, 10);

            addToCart(productName, productPrice, productImage, quantity);
        });
    }

    fetchCart();
});
