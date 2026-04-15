// VÉNARO eCommerce Presentation Script
// Full JavaScript logic (Cart, Wishlist, AJAX Checkout, Real-time search) 
// is available in the premium source code package.

document.addEventListener('DOMContentLoaded', () => {
    console.log("%c VÉNARO Premium Source Code ", "background: #111; color: #fff; font-size: 20px; font-weight: bold; padding: 10px;");
    console.log("Welcome to the presentation. The full AJAX/Fetch API logic is available upon purchase.");
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
