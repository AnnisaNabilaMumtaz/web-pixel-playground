const tabs = document.querySelectorAll('.tab');
const tabPanes = document.querySelectorAll('.tab-pane');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    // Hapus kelas aktif dari tab dan konten yang sekarang
    document.querySelector('.tab.active').classList.remove('active');
    document.querySelector('.tab-pane.active').classList.remove('active');

    // Tambahkan kelas aktif ke tab dan konten yang diklik
    tab.classList.add('active');
    document.getElementById(tab.getAttribute('data-tab')).classList.add('active');
  });
});

// Slider functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const prevSlideButton = document.querySelector('.prev-slide');
const nextSlideButton = document.querySelector('.next-slide');

function showSlide(index) {
  slides[currentSlide].classList.remove('active');
  currentSlide = (index + slides.length) % slides.length;
  slides[currentSlide].classList.add('active');
}

prevSlideButton.addEventListener('click', () => showSlide(currentSlide - 1));
nextSlideButton.addEventListener('click', () => showSlide(currentSlide + 1));