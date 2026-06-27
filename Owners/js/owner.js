document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("menu-toggle");
    const nav = document.getElementById("nav-menu");

    if(toggle && nav){
        toggle.addEventListener("click", () => {
            nav.classList.toggle("show");
        });

        document.querySelectorAll("#nav-menu a").forEach(link => {
            link.addEventListener("click", () => {
                nav.classList.remove("show");
            });
        });
    }
});