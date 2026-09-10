</div> <!-- End main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    // Toggle sidebar
    sidebarToggle.addEventListener('click', function(e) {
        sidebar.classList.toggle('show');
        e.stopPropagation();
    });

    // Tutup sidebar jika user mengklik area di luar sidebar
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
</script>
</body>
</html>
