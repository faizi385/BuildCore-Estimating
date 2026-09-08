// Mobile navigation
const menuToggle = document.getElementById('menuToggle');
const mainNav = document.getElementById('mainNav');
if (menuToggle) {
  menuToggle.addEventListener('click', () => mainNav.classList.toggle('open'));
}

// Hero slider
const slides = [...document.querySelectorAll('.hero-slide')];
const dots = [...document.querySelectorAll('.dot')];
let currentSlide = 0;
let sliderTimer;

function showSlide(index) {
  currentSlide = (index + slides.length) % slides.length;
  
  // Reset animations for all slides
  slides.forEach((slide) => {
    const elements = slide.querySelectorAll('.eyebrow, .hero h1, .hero p, .hero-actions');
    elements.forEach(el => {
      el.style.animation = 'none';
      el.offsetHeight; // Trigger reflow
    });
  });
  
  slides.forEach((slide, i) => {
    slide.classList.toggle('active', i === currentSlide);
    if (i === currentSlide) {
      // Re-trigger animations for active slide
      const elements = slide.querySelectorAll('.eyebrow, .hero h1, .hero p, .hero-actions');
      elements.forEach(el => {
        el.style.animation = '';
      });
    }
  });
  
  dots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
}
function startSlider() {
  sliderTimer = setInterval(() => showSlide(currentSlide + 1), 5000);
}
dots.forEach((dot, i) => dot.addEventListener('click', () => {
  clearInterval(sliderTimer);
  showSlide(i);
  startSlider();
}));
startSlider();

// Services horizontal slider with auto-scroll
const track = document.getElementById('serviceTrack');
let autoScrollInterval;
let isPaused = false;

function autoScroll() {
  if (!track || isPaused) return;
  
  const cardWidth = 320 + 14; // card width + gap
  const maxScroll = track.scrollWidth - track.clientWidth;
  
  if (track.scrollLeft >= maxScroll - 10) {
    track.scrollTo({left: 0, behavior: 'smooth'});
  } else {
    track.scrollBy({left: cardWidth, behavior: 'smooth'});
  }
}

function startAutoScroll() {
  autoScrollInterval = setInterval(autoScroll, 1000);
}

function stopAutoScroll() {
  clearInterval(autoScrollInterval);
}

// Start auto-scroll
if (track) {
  startAutoScroll();
  
  // Pause on hover
  track.addEventListener('mouseenter', () => {
    isPaused = true;
  });
  
  track.addEventListener('mouseleave', () => {
    isPaused = false;
  });
}

// Manual controls
document.getElementById('servicePrev')?.addEventListener('click', () => {
  stopAutoScroll();
  track.scrollBy({left: -334, behavior: 'smooth'});
  setTimeout(startAutoScroll, 3000);
});

document.getElementById('serviceNext')?.addEventListener('click', () => {
  stopAutoScroll();
  track.scrollBy({left: 334, behavior: 'smooth'});
  setTimeout(startAutoScroll, 3000);
});

// FAQ accordion
document.querySelectorAll('.faq-item button').forEach(button => {
  button.addEventListener('click', () => {
    const item = button.parentElement;
    document.querySelectorAll('.faq-item').forEach(other => {
      if (other !== item) other.classList.remove('open');
    });
    item.classList.toggle('open');
  });
});

// FAQ assistance card animation
const faqAssistCard = document.querySelector('.faq-assist-card');
if (faqAssistCard) {
  setTimeout(() => {
    faqAssistCard.classList.add('visible');
  }, 300);
}
