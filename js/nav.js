document.addEventListener("DOMContentLoaded", function () {
    var navToggle = document.getElementById("navToggle");
    var navLinks = document.querySelector(".nav-links");
    var overlay = document.getElementById("overlay");

    function toggleNav(event) {
        event.preventDefault(); // Prevent default behavior

        document.body.classList.toggle("noscroll");
        navToggle.classList.toggle("change");
        overlay.classList.toggle("show"); // Toggle overlay visibility

        if (navLinks.classList.contains("show")) {
            closeNav();
        } else {
            openNav();
        }
    }

    navToggle.addEventListener("click", toggleNav);

    overlay.addEventListener("click", function(event) {
        if (event.target === overlay) {
            toggleNav(event);
        }
    });

    function openNav() {
        navLinks.classList.add("show");
        navLinks.style.transition = "opacity 0.3s ease";
        navLinks.style.opacity = "0"; // Ensure it starts hidden
        setTimeout(function () {
            navLinks.style.opacity = "1";
        }, 50); // Adjust the delay as needed
    }

    function closeNav() {
        navLinks.style.opacity = "0";
        setTimeout(function () {
            navLinks.classList.remove("show");
            navLinks.style.transition = "opacity 0.3s ease-in-out";
            navLinks.style.opacity = "1";
        }, 200); // Adjust the delay to match the transition duration
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const nav = document.querySelector('nav');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 0) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
});
