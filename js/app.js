document.addEventListener("DOMContentLoaded", () => {  //Document Object Model (DOM)
    console.log("Welcome to Velvet Vogue!");
});

//products_list_.php
function addToCart() {
    alert("Product added to cart!");
}


//contact form validation
document.querySelector('form').addEventListener('submit', function(event) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (!name || !email || !message) {
        alert('All fields are required!');
        event.preventDefault(); // Prevent form submission if validation fails
    }
});


// Disable Right-Click
document.addEventListener("contextmenu", (e) => {
    e.preventDefault();
    alert("Right-click is disabled on this site.");
});

// Disable Keyboard Shortcuts for "View Page Source" and "Inspect"
document.addEventListener("keydown", (e) => {
    // Block F12 (Inspect)
    if (e.key === "F12") {
        e.preventDefault();
        alert("Inspect option is disabled.");
    }

    // Block Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U (View Source), Ctrl+Shift+C
    if (e.ctrlKey && (e.shiftKey && ["I", "J", "C"].includes(e.key)) || e.ctrlKey && e.key === "U") {
        e.preventDefault();
        alert("View source and inspect are disabled.");
    }
});


