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