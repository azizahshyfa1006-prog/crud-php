<!-- CDN Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Asset Plugin DataTables -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<!-- Load FontAwesome with CDN -->
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>

<!-- Load CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>

<!-- Skrip Inisialisasi CKEditor untuk Alamat (hanya jalan jika ada textarea alamat) -->
<script>
    if (document.getElementById('alamat')) {
        CKEDITOR.replace('alamat', {
            filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
            filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            height: '400px'
        });
    }
</script>

<!-- Skrip Inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>

</body>
</html>