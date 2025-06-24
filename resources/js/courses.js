// Initialize AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

// Course Card Hover Effects
document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading for images
    const images = document.querySelectorAll('.course-image');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Search input animation
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('focused');
        });

        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('focused');
        });
    }

    // Category filter animation
    const categoryLinks = document.querySelectorAll('.category-link');
    categoryLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            categoryLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            
            // Animate course cards out
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            });

            // Fetch filtered courses
            fetch(link.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newCourses = doc.querySelector('.courses-grid');
                    
                    // Animate new courses in
                    setTimeout(() => {
                        document.querySelector('.courses-grid').innerHTML = newCourses.innerHTML;
                        const newCards = document.querySelectorAll('.course-card');
                        newCards.forEach(card => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
                    }, 300);
                });
        });
    });

    // Course card hover effects
    const courseCards = document.querySelectorAll('.course-card');
    courseCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            const image = card.querySelector('.course-image');
            const overlay = card.querySelector('.course-overlay');
            const badge = card.querySelector('.category-badge');
            
            if (image) image.style.transform = 'scale(1.1)';
            if (overlay) overlay.style.opacity = '1';
            if (badge) {
                badge.style.opacity = '1';
                badge.style.transform = 'translateY(0)';
            }
        });

        card.addEventListener('mouseleave', () => {
            const image = card.querySelector('.course-image');
            const overlay = card.querySelector('.course-overlay');
            const badge = card.querySelector('.category-badge');
            
            if (image) image.style.transform = 'scale(1)';
            if (overlay) overlay.style.opacity = '0';
            if (badge) {
                badge.style.opacity = '0';
                badge.style.transform = 'translateY(-10px)';
            }
        });
    });

    // Lesson list accordion
    const lessonItems = document.querySelectorAll('.lesson-item');
    lessonItems.forEach(item => {
        item.addEventListener('click', () => {
            const content = item.querySelector('.lesson-content');
            if (content) {
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
                item.classList.toggle('active');
            }
        });
    });

    // Enrollment button effect
    const enrollButton = document.querySelector('.enroll-button');
    if (enrollButton) {
        enrollButton.addEventListener('mouseenter', () => {
            enrollButton.querySelector('::after').style.transform = 'translateX(100%)';
        });
    }

    // Progress bar animation for enrolled courses
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const progress = bar.dataset.progress;
        bar.style.width = `${progress}%`;
    });
});

// Smooth scroll to course sections
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Course preview video modal
function openVideoModal(videoUrl) {
    const modal = document.createElement('div');
    modal.className = 'video-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <iframe src="${videoUrl}" frameborder="0" allowfullscreen></iframe>
        </div>
    `;
    document.body.appendChild(modal);
    
    modal.querySelector('.close-modal').addEventListener('click', () => {
        modal.remove();
    });
}

// Search autocomplete
let searchTimeout;
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = e.target.value;
            if (query.length >= 2) {
                fetch(`/api/courses/search?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        const resultsContainer = document.querySelector('.search-results');
                        resultsContainer.innerHTML = data.courses.map(course => `
                            <div class="search-result-item">
                                <img src="${course.thumbnail}" alt="${course.title}">
                                <div>
                                    <h4>${course.title}</h4>
                                    <p>${course.description}</p>
                                </div>
                            </div>
                        `).join('');
                    });
            }
        }, 300);
    });
} 