<!-- footer.php -->
<footer style="background-color: #333; color: white; padding: 20px 0; text-align: center; position: relative; width: 100%; bottom: 0;">
    <div class="footer-container">
        <h3 style="margin-bottom: 20px;">Follow Us & Contact</h3>
        <div class="social-links">
            <a href="https://www.instagram.com/" target="_blank" style="color: white; margin: 0 15px; text-decoration: none;">
                <i class="fab fa-instagram" style="font-size: 24px;"></i> Instagram
            </a>
            <a href="https://github.com/Judah-254/Urban_Jerseys" target="_blank" style="color: white; margin: 0 15px; text-decoration: none;">
                <i class="fab fa-github" style="font-size: 24px;"></i> GitHub
            </a>
            <a href="tel:+254769 00 57 88" style="color: white; margin: 0 15px; text-decoration: none;">
                <i class="fas fa-phone-alt" style="font-size: 24px;"></i> +254769 00 57 88
            </a>
            <a href="mailto:info@UrbanJersey.com" style="color: white; margin: 0 15px; text-decoration: none;">
                <i class="fas fa-envelope" style="font-size: 24px;"></i> info@UrbanJersey.com
            </a>
        </div>
        <p style="margin-top: 20px; font-size: 14px;">&copy; <?php echo date('Y'); ?> Urban Jerseys Store. All Rights Reserved.</p>
    </div>

    <style>
        /* Ensure the footer stays at the bottom */
        body, html {
            height: 100%;
            margin: 0;
        }

        /* Add some padding to the bottom of the main content so it doesn't overlap */
        .main-content {
            padding-bottom: 60px; /* Adjust based on footer height */
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .social-links a {
            display: inline-block;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .social-links a:hover {
            color: #007bff;
            transform: scale(1.1);
        }

        footer {
            position: relative;
            bottom: 0;
            width: 100%;
            background-color: #333;
            padding: 20px 0;
        }
    </style>

    <!-- FontAwesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</footer>
