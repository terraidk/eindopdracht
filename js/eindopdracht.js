document.addEventListener("DOMContentLoaded", function () {
    const hamburgerMenu = document.querySelector(".hamburger-menu");
    const navMenu = document.querySelector(".menu ul");

    function toggleMenu() {
        hamburgerMenu.classList.toggle("active");
        navMenu.classList.toggle("active");
    }

    hamburgerMenu.addEventListener("click", toggleMenu);

    const menuLinks = document.querySelectorAll(".navbar-li a");
    menuLinks.forEach(link => {
        link.addEventListener("click", toggleMenu);
    });

    toggleMenuDisplay(); // Call the function initially

    screenWidthQuery.addListener(toggleMenuDisplay); // Listen for changes in screen width

    const inventoryLink = document.querySelector("#inventory");
    const servicesLink = document.querySelector("#services");
    const contactLink = document.querySelector("#contact");
    const locationLink = document.querySelector("#location");

    // Add event listeners to menu links
    if (inventoryLink) {
        inventoryLink.addEventListener("click", function (e) {
            e.preventDefault();
            var targetSection = document.querySelector("#inventory1");
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    }

    if (servicesLink) {
        servicesLink.addEventListener("click", function (e) {
            e.preventDefault();
            var targetSection = document.querySelector("#services1");
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    }

    if (contactLink) {
        contactLink.addEventListener("click", function (e) {
            e.preventDefault();
            var targetSection = document.querySelector("#contact1");
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    }

    if (locationLink) {
        locationLink.addEventListener("click", function (e) {
            e.preventDefault();
            var targetSection = document.querySelector("#location1");
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    }

    const winkel = document.getElementById("winkel");
    const winkelMand = document.getElementById("pr-winkel");

    // Show/hide the shopping cart when clicked
    if (winkel) {
        winkel.addEventListener("click", () => {
            winkel.classList.toggle("active");
            winkelMand.classList.toggle("active");
        });
    }

    const boxlog = document.getElementById("boxlog");
    const uitloggen = document.getElementById("uitloggen");

    // Show/hide user profile and logout button
    if (boxlog) {
        boxlog.addEventListener("click", () => {
            boxlog.classList.toggle("drop");
            uitloggen.classList.toggle("drop");
        });
    }

    const frontfaceLinks = document.querySelectorAll(".frontface-a");

    // Close the mobile menu when a link is clicked
    frontfaceLinks.forEach((link) => {
        link.addEventListener("click", () => {
            hamburgerMenu.classList.remove("active");
            navMenu.classList.remove("active");
        });
    });

    const hamburgermenu = document.querySelector(".hamburger-menu");
    const menu = document.querySelector(".menu ul");

    // Toggle hamburger menu and menu items on click
    hamburgermenu.addEventListener("click", () => {
        hamburgermenu.classList.toggle("active");
        menu.classList.toggle("active");
    });
});
