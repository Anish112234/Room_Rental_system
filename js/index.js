    const mode = document.querySelector("#darkMode");
    const head = document.querySelector(".head");


mode.addEventListener("click",()=>{
  document.body.classList.toggle("dark");
})










    // if (mode && head) {
    //   mode.addEventListener("click", () => {
    //     // Get the current background color (computed, not just inline)
    //     const currentBg = getComputedStyle(head).backgroundColor;

    //     if (currentBg === "rgb(0, 0, 0)") { // black
    //       head.style.backgroundColor = "white";
    //       head.style.color = "black";
    //     } else {
    //       head.style.backgroundColor = "black";
    //       head.style.color = "white";
    //     }
    //   });
    // } else {
    //   console.error("Element(s) not found in the DOM.");
    // }



document.querySelectorAll(".btn").forEach(button => {
    button.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Please login first to book a room!");
    });
});


//for Hamburger menu ko laghi
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("menu-toggle");
    const nav = document.getElementById("nav-menu");

    toggle.addEventListener("click", () => {
        nav.classList.toggle("show");
    });


    
    // optional: close menu when clicking a link (mobile UX improvement)
    document.querySelectorAll(".content a").forEach(link => {
        link.addEventListener("click", () => {
            nav.classList.remove("show");
        });
    });
});


