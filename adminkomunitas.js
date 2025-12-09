// Fungsi untuk membuka popup dan memuat form edit menggunakan AJAX
function openEditPopup(id) {
    // Menampilkan popup
    $('#editPopup').show();

    // Memuat konten form edit menggunakan AJAX
    $.ajax({
        url: 'edit_komunitas.php', // Halaman untuk menampilkan form edit
        type: 'GET',
        data: { id: id }, // Mengirimkan id komunitas yang akan diedit
        success: function(response) {
            $('#editPopupContent').html(response); // Menampilkan hasil AJAX dalam popup
        }
    });
}

// Fungsi untuk menutup popup
function closeEditPopup() {
    $('#editPopup').hide();
}
