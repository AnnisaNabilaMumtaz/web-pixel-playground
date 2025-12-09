function openEditModal(id_game, nama_game, developer, batas_usia, deskripsi, foto_game, link_game, link_mabar, link_komunitas) {
    document.getElementById('editGameId').value = id_game;
    document.getElementById('editGameName').value = nama_game;
    document.getElementById('editDeveloper').value = developer;
    document.getElementById('editRating').value = batas_usia;
    document.getElementById('editDescription').value = deskripsi;
    document.getElementById('editDownloadLink').value = link_game;
    document.getElementById('editDiscordLink').value = link_mabar;
    document.getElementById('editCommunityLink').value = link_komunitas;

    // Update preview image
    const currentImage = document.getElementById('currentGameImage');
    currentImage.src = 'uploads/' + foto_game;
    currentImage.alt = nama_game;

    // Show modal
    const editGameModal = new bootstrap.Modal(document.getElementById('editGameModal'));
    editGameModal.show();
}
