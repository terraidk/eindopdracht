document.addEventListener("DOMContentLoaded", function () {
    var scroll = new SmoothScroll('a[href*="#"]');

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

    toggleMenuDisplay(); 

    screenWidthQuery.addListener(toggleMenuDisplay); 

    const boxlog = document.getElementById("boxlog");
    const uitloggen = document.getElementById("uitloggen");

    if (boxlog) {
        boxlog.addEventListener("click", () => {
            boxlog.classList.toggle("drop");
            uitloggen.classList.toggle("drop");
        });
    }

    const frontfaceLinks = document.querySelectorAll(".frontface-a");

    frontfaceLinks.forEach((link) => {
        link.addEventListener("click", () => {
            hamburgerMenu.classList.remove("active");
            navMenu.classList.remove("active");
        });
    });

    const hamburgermenu = document.querySelector(".hamburger-menu");
    const menu = document.querySelector(".menu ul");

    hamburgermenu.addEventListener("click", () => {
        hamburgermenu.classList.toggle("active");
        menu.classList.toggle("active");
    });
});


