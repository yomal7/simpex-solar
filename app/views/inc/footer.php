        </div> <!-- End main-content -->
    </div> <!-- End wrapper -->
    
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> SimplEx Solar Solutions. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Common scripts
        document.addEventListener('DOMContentLoaded', function() {
            // Auto close alerts after 4 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 4000);
            });
        });
    </script>
</body>
</html>