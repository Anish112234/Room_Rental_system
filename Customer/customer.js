document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.getElementById("menu-toggle");
    const nav = document.getElementById("nav-menu");

    if(toggle && nav){
        toggle.addEventListener("click", () => {
            nav.classList.toggle("show");
        });
    }

    console.log("menu loaded");
});