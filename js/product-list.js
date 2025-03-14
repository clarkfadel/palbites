        // Function to apply filter
        function applyFilter(category = '') {
            let available = document.getElementById('available').checked ? '1' : '';
            let search = document.getElementById('searchBox').value.trim();

            window.location.href = "products.php?category=" + category + "&available=" + available + "&search=" + encodeURIComponent(search);
        }

        // Reset filters and redirect on reload
        window.addEventListener('DOMContentLoaded', function() {
            if (performance.navigation.type === 1) { // Detects page refresh
                window.location.href = "products.php"; // Redirects to reset filters
            } else {
                document.getElementById('available').checked = true; // Only Available checked
                document.getElementById('searchBox').value = ''; // Clear search box
                let categoryFilters = document.querySelectorAll('.category-checkbox'); // Select category checkboxes
                categoryFilters.forEach(filter => filter.checked = false); // Uncheck categories
            }
        });

        // Trigger search on Enter key
        document.getElementById('searchBox').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                applyFilter();
            }
        });

        // Trigger search when clicking the search icon
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
                let productsCount = productsGrid.children.length; // Count product items

                if (productsCount <= 2) {
                    pagination.style.marginTop = "20px"; // Bring it closer if only 1-2 products
                } else {
                    pagination.style.marginTop = "50px"; // Normal margin for more products
                }
            }
        }