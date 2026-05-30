document.querySelectorAll(".btn").forEach(button => {
    button.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Please login first to book a room!");
    });
});
