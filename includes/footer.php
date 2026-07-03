<?php
// Includes/footer.php
?>
    <!-- Toast Notification Container (Automatic fallback) -->
    <div id="custom-toast-container"></div>

    <!-- Bootstrap 5 Bundle JS (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main Vanilla JavaScript -->
    <script src="assets/js/main.js"></script>

    <!-- Trigger Toast Notification if set in Session -->
    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                NotificationToast.show(
                    "<?= htmlspecialchars($_SESSION['toast']['title']) ?>",
                    "<?= htmlspecialchars($_SESSION['toast']['message']) ?>",
                    "<?= htmlspecialchars($_SESSION['toast']['type']) ?>"
                );
            });
        </script>
        <?php unset($_SESSION['toast']); // Clear after showing ?>
    <?php endif; ?>
</body>
</html>
