        <?php
        $isAboutPage = isset($_GET['url']) && strpos($_GET['url'], 'about') !== false;
        ?>
        <footer class="site-footer <?php echo $isAboutPage ? 'about-footer' : ''; ?>">
            <h3>This is the footer</h3>
        </footer>
    </body>
</html>
