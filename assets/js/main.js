/**
 * JavaScript Principal Farol de Luz
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

document.addEventListener('DOMContentLoaded', function() {
    initParticles();
    initMobileMenu();
    initCarousel();
    initNewsletterForms();
});

function initParticles() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }
    
    const canvas = document.getElementById('particles-canvas');
    if (!canvas) return;
    
    const heroSection = document.getElementById('hero-section');
    if (!heroSection) return;
    
    const ctx = canvas.getContext('2d');
    let particles = [];
    let mouseX = 0;
    let mouseY = 0;
    let canvasOffsetTop = 0;
    
    function resizeCanvas() {
        canvas.width = heroSection.offsetWidth;
        canvas.height = heroSection.offsetHeight;
        canvasOffsetTop = heroSection.offsetTop;
    }
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    class Particle {
        constructor() {
            this.reset();
            this.y = Math.random() * canvas.height;
        }
        
        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * 0.5 - 0.25;
            this.speedY = Math.random() * 0.5 - 0.25;
            this.opacity = Math.random() * 0.5 + 0.3;
        }
        
        update() {
            const dx = mouseX - this.x;
            const dy = mouseY - this.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            const maxDistance = 150;
            
            if (distance < maxDistance) {
                const force = (maxDistance - distance) / maxDistance;
                const angle = Math.atan2(dy, dx);
                this.x -= Math.cos(angle) * force * 3;
                this.y -= Math.sin(angle) * force * 3;
            }
            
            this.x += this.speedX;
            this.y += this.speedY;
            
            if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
            if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            
            this.x = Math.max(0, Math.min(canvas.width, this.x));
            this.y = Math.max(0, Math.min(canvas.height, this.y));
        }
        
        draw() {
            ctx.fillStyle = `rgba(242, 201, 125, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    
    for (let i = 0; i < 100; i++) {
        particles.push(new Particle());
    }
    
    heroSection.addEventListener('mousemove', (e) => {
        const rect = heroSection.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    });
    
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });
        
        requestAnimationFrame(animate);
    }
    
    animate();
}

function initMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    
    if (!toggle || !menu) return;
    
    toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
}

function initCarousel() {
    const carousel = document.getElementById('dialogos-carousel');
    if (!carousel) return;
    
    const track = carousel.querySelector('#dialogos-track');
    const prevBtn = carousel.querySelector('#dialogos-prev');
    const nextBtn = carousel.querySelector('#dialogos-next');
    
    if (!track || !prevBtn || !nextBtn) return;
    
    let currentIndex = 0;
    const items = track.children;
    const totalItems = items.length;
    
    function getItemsPerView() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }
    
    function updateCarousel() {
        const itemsPerView = getItemsPerView();
        const maxIndex = Math.max(0, totalItems - itemsPerView);
        currentIndex = Math.min(currentIndex, maxIndex);
        
        const offset = -(currentIndex * (100 / itemsPerView));
        track.style.transform = `translateX(${offset}%)`;
        
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= maxIndex;
    }
    
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });
    
    nextBtn.addEventListener('click', () => {
        const itemsPerView = getItemsPerView();
        const maxIndex = Math.max(0, totalItems - itemsPerView);
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        }
    });
    
    window.addEventListener('resize', updateCarousel);
    updateCarousel();
    
    let startX = 0;
    let isDragging = false;
    
    track.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
    });
    
    track.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
    });
    
    track.addEventListener('touchend', (e) => {
        if (!isDragging) return;
        isDragging = false;
        
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;
        
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextBtn.click();
            } else {
                prevBtn.click();
            }
        }
    });
}

function initNewsletterForms() {
    const forms = document.querySelectorAll('#newsletter-form, #footer-newsletter-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            const messageEl = form.parentElement.querySelector('[id$="-message"]');
            
            if (!messageEl) return;
            
            messageEl.textContent = 'Processando...';
            messageEl.className = 'mt-4 text-lg text-cinza-azulado';
            
            try {
                const response = await fetch('/api/newsletter/subscribe', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.ok) {
                    messageEl.textContent = data.message;
                    messageEl.className = 'mt-4 text-lg text-green-400';
                    form.reset();
                } else {
                    messageEl.textContent = data.message;
                    messageEl.className = 'mt-4 text-lg text-red-400';
                }
            } catch (error) {
                messageEl.textContent = 'Erro ao processar inscrição. Tente novamente.';
                messageEl.className = 'mt-4 text-lg text-red-400';
            }
        });
    });
}
