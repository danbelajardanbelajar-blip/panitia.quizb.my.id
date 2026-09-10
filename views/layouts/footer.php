    </main> <!-- end content-wrapper -->
</div> <!-- end main-content -->

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    // Inisialisasi Icons
    lucide.createIcons();

    // Sidebar Mobile Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            toggleSidebar();
        });
    }

    // Animation: form focus rings and inputs
    const inputs = document.querySelectorAll('.form-control-modern, .form-select-modern');
    inputs.forEach(input => {
        // Just empty listener for now to trigger CSS animations if needed
    });
</script>
</body>
</html>
