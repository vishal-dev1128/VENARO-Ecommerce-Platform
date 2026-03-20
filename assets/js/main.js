//** VÉNARO eCommerce Platform - Core Logic */JavaScript
// Version: 1.0

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Material Design components
    initializeMDB();

    // Add to cart functionality
    initializeAddToCart();

    // Wishlist functionality
    initializeWishlist();

    // Newsletter subscription
    initializeNewsletter();

    // Image lazy loading
    initializeLazyLoading();

    // Reveal on scroll
    initializeRevealOnScroll();
});

// Reveal on scroll
function initializeRevealOnScroll() {
    const reveals = document.querySelectorAll('.reveal-on-scroll');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.1
    });

    reveals.forEach(reveal => {
        revealObserver.observe(reveal);
        // If already in view (fallback for some mobile browsers)
        if (reveal.getBoundingClientRect().top < window.innerHeight) {
            reveal.classList.add('active');
        }
    });
}

// Initialize MDB components
function initializeMDB() {
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-mdb-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new mdb.Tooltip(tooltipTriggerEl);
    });

    // Ripple effect
    const rippleElements = document.querySelectorAll('.btn');
    rippleElements.forEach(element => {
        new mdb.Ripple(element);
    });
}

// Add to Cart
function initializeAddToCart() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const productId = this.dataset.productId;
            const variantId = this.dataset.variantId || null;
            const quantity = this.dataset.quantity || 1;

            addToCart(productId, variantId, quantity);
        });
    });
}

function addToCart(productId, variantId, quantity) {
    const formData = new FormData();
    formData.append('product_id', productId);
    if (variantId) formData.append('variant_id', variantId);
    formData.append('quantity', quantity);

    fetch('/new-venaro/api/cart-add.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to cart!', 'success');
                updateCartBadge(data.cart_count);
            } else {
                showNotification(data.message || 'Failed to add product', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
}

// Wishlist
function initializeWishlist() {
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');

    wishlistButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.dataset.productId;
            toggleWishlist(productId, this);
        });
    });
}

function toggleWishlist(productId, button) {
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('/new-venaro/api/wishlist-toggle.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = button.querySelector('i');
                if (data.action === 'added') {
                    icon.textContent = 'favorite';
                    showNotification('Added to wishlist', 'success');
                } else {
                    icon.textContent = 'favorite_border';
                    showNotification('Removed from wishlist', 'success');
                }
                updateWishlistBadge(data.wishlist_count);
            } else {
                if (data.message === 'Not logged in') {
                    window.location.href = '/new-venaro/login.php';
                } else {
                    showNotification(data.message || 'Failed to update wishlist', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
}

// Newsletter
function initializeNewsletter() {
    const newsletterForm = document.getElementById('newsletterForm');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/new-venaro/api/newsletter-subscribe.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Successfully subscribed! Check your email for discount code.', 'success');
                        this.reset();
                    } else {
                        showNotification(data.message || 'Subscription failed', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred', 'error');
                });
        });
    }
}

// Lazy Loading
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Update wishlist badge
function updateWishlistBadge(count) {
    const badge = document.getElementById('wishlist-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';

    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
    `;

    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Format price
function formatPrice(amount) {
    return 'Rs. ' + parseFloat(amount).toFixed(2);
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
