function applyFilter(category = '') {
    let available = document.getElementById('available').checked ? '1' : '';
    let search = document.getElementById('searchBox').value.trim();

    window.location.href = "products.php?category=" + category + "&available=" + available + "&search=" + encodeURIComponent(search);
}

window.addEventListener('DOMContentLoaded', function() {
    if (performance.navigation.type === 1) { 
        window.location.href = "products.php"; 
    } else {
        document.getElementById('available').checked = true; 
        document.getElementById('searchBox').value = ''; 
        let categoryFilters = document.querySelectorAll('.category-checkbox');
        categoryFilters.forEach(filter => filter.checked = false); 
    }
});

document.getElementById('searchBox').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        applyFilter();
    }
});

document.getElementById('searchButton').addEventListener('click', function() {
    applyFilter();
});

window.addEventListener("DOMContentLoaded", function () {
    adjustPaginationMargin();
});

function adjustPaginationMargin() {
    let productsGrid = document.querySelector(".products-grid");
    let pagination = document.querySelector(".pagination");

    if (productsGrid && pagination) {
        let productsCount = productsGrid.children.length; 

        if (productsCount <= 2) {
            pagination.style.marginTop = "20px"; 
        } else {
            pagination.style.marginTop = "50px"; 
        }
    }
}