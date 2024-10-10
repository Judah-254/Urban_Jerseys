document.addEventListener('DOMContentLoaded', () => {
    // Functionality for Add to Cart buttons
    const cartButtons = document.querySelectorAll('.add-to-cart-btn');

    cartButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Added to cart!');
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        // Event delegation for dynamically loaded suggestion items
        document.getElementById('suggestions').addEventListener('click', function (e) {
            if (e.target && e.target.matches('.suggestion-item')) {
                // Get the product id from the clicked suggestion
                const productId = e.target.getAttribute('data-id');

                // Redirect to the product page based on the product ID
                if (productId) {
                    window.location.href = 'product.php?id=' + productId;
                }
            }
        });
    });

    // Show search suggestions as the user types
    function showSuggestions(str) {
        if (str.length === 0) {
            document.getElementById("suggestions").innerHTML = "";
            document.getElementById("suggestions").style.display = "none";
            return;
        } else {
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    // Display suggestions
                    document.getElementById("suggestions").innerHTML = this.responseText;
                    document.getElementById("suggestions").style.display = "block";
                }
            };
            xhttp.open("GET", "search_suggestions.php?query=" + encodeURIComponent(str), true);
            xhttp.send();
        }
    }
}