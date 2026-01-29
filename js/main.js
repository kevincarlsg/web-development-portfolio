/* =========================
   SCROLL ANIMATIONS
========================= */
const observer = new IntersectionObserver(
  entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('show');
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.15 }
);

document
  .querySelectorAll('.slide-up, .fade-in, .fade-in-delay')
  .forEach(el => observer.observe(el));


/* =========================
   SMOOTH SCROLL
========================= */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', e => {
    e.preventDefault();
    document
      .querySelector(anchor.getAttribute('href'))
      ?.scrollIntoView({ behavior: 'smooth' });
  });
});


/* =========================
   MODAL BASE
========================= */
const modal = document.getElementById('projectModal');
const modalBody = document.getElementById('modalBody');
const closeBtn = document.getElementById('closeBtn');

let activeImages = [];
let currentImageIndex = 0;

const closeModal = () => {
  modal.style.display = 'none';
  modalBody.innerHTML = '';
  activeImages = [];
};

closeBtn.onclick = closeModal;

window.addEventListener('click', e => {
  if (e.target === modal) closeModal();
});

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
});


/* =========================
   VIDEO MODAL (YOUTUBE FIX)
========================= */
function openVideoModal(url) {
  const videoId = url.includes("youtu.be")
    ? url.split("youtu.be/")[1].split("?")[0]
    : url.split("v=")[1].split("&")[0];

  const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1`;

  modalBody.innerHTML = `
    <div class="video-wrapper">
      <iframe
        class="modal-video"
        src="${embedUrl}"
        frameborder="0"
        allow="autoplay; encrypted-media"
        allowfullscreen>
      </iframe>
    </div>
  `;

  modal.style.display = 'flex';
}


/* =========================
   GALLERY SLIDER
========================= */
function setupGallery(images) {
  activeImages = images;
  currentImageIndex = 0;
  updateGallery();
  modal.style.display = 'flex';
}

function updateGallery() {
  modalBody.innerHTML = `
    <div class="slider-container">
      <button class="prev">&#10094;</button>
      <div class="slide-view">
        <img src="${activeImages[currentImageIndex]}" class="active-slide">
      </div>
      <button class="next">&#10095;</button>
    </div>
    <div class="counter-display">
      ${currentImageIndex + 1} / ${activeImages.length}
    </div>
  `;

  modalBody.querySelector('.prev').onclick = () => changeSlide(-1);
  modalBody.querySelector('.next').onclick = () => changeSlide(1);
}

function changeSlide(step) {
  currentImageIndex =
    (currentImageIndex + step + activeImages.length) % activeImages.length;
  updateGallery();
}


/* =========================
   RENDER DATA
========================= */
document.addEventListener('DOMContentLoaded', () => {
  renderTools();
  renderProjects();
});

function renderTools() {
  const container = document.getElementById('tools-container');
  if (!container) return;

  container.innerHTML = PORTFOLIO_DATA.categories
    .map(cat => `
      <h4 class="category-title">${cat.title}</h4>
      <div class="tools-grid">
        ${cat.tools
          .map(
            t => `
          <div class="tool-card">
            <i class="${t.icon}"></i>
            <span>${t.name}</span>
          </div>`
          )
          .join('')}
      </div>
    `)
    .join('');
}

function renderProjects() {
  const container = document.getElementById('projects-list');
  if (!container) return;

  container.innerHTML = PORTFOLIO_DATA.projects
    .map(p => {
      let buttons = '';

      if (p.videoSource) {
        buttons += `
          <button class="btn-demo"
            onclick="openVideoModal('${p.videoSource}')">
            Ver Demo
          </button>`;
      }

      buttons += `
        <button class="btn-gallery"
          onclick='setupGallery(${JSON.stringify(p.gallery)})'>
          Ver Galería
        </button>
      `;

      buttons += `
        <a href="${p.github}"
          class="btn-github"
          target="_blank"
          rel="noopener noreferrer">
          GitHub
        </a>
      `;

      return `
        <article class="project-card">
          <div class="project-image">
            <img src="${p.mainImage}" alt="${p.title}">
          </div>
          <div class="project-content">
            <h4>${p.title}</h4>
            <p>${p.description}</p>
            <span class="tech-stack">${p.tech}</span>
            <div class="links">${buttons}</div>
          </div>
        </article>
      `;
    })
    .join('');
}
