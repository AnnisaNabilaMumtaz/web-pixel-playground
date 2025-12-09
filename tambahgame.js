// Show the popup when the "Tambah Game Baru" button is clicked
var addGameButton = document.querySelector('.btn-primary');
var addGamePopup = document.getElementById('addGamePopup');
var closeButton = document.querySelector('.close-button');

addGameButton.addEventListener('click', function() {
  addGamePopup.style.display = 'block';
});

closeButton.addEventListener('click', function() {
  addGamePopup.style.display = 'none';
});

window.addEventListener('click', function(event) {
  if (event.target == addGamePopup) {
    addGamePopup.style.display = 'none';
  }
});