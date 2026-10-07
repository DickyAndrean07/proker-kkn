import 'leaflet/dist/leaflet.css';
import { initMap } from './map.js';
import { initDashboardCharts } from './dashboard-charts.js';
import { initAiAssistant } from './ai-assistant.js';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Expose initMap globally
window.initMap = initMap;

// Marker for JS availability (enables CSS scroll-reveal guards)
document.documentElement.classList.add('js');

// Interactive Detail Modal Handlers
window.openDetailModal = function(title, tag, text) {
    const head = document.getElementById('modal-head');
    const tagEl = document.getElementById('modal-tag');
    const content = document.getElementById('modal-content');
    const overlay = document.getElementById('modal-overlay');
    if (head) head.textContent = title;
    if (tagEl) tagEl.textContent = tag;
    if (content) content.textContent = text;
    if (overlay) overlay.classList.add('active');
};

window.closeDetailModal = function(e) {
    if (!e || e.target.id === 'modal-overlay' || e.target.classList.contains('modal-close-btn') || e.target.classList.contains('modal-action-btn')) {
        const overlay = document.getElementById('modal-overlay');
        if (overlay) overlay.classList.remove('active');
    }
};

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const overlay = document.getElementById('modal-overlay');
        if (overlay) overlay.classList.remove('active');
    }
});

// GSAP ScrollTrigger Section Animations (Fail-safe, 100% Visible)
function initScrollAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        return;
    }

    // Respect reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    // 1. Reading Progress Bar at Top
    const progressBar = document.getElementById('scroll-progress');
    if (progressBar) {
        gsap.to(progressBar, {
            scaleX: 1,
            ease: "none",
            scrollTrigger: {
                trigger: document.body,
                start: "top top",
                end: "bottom bottom",
                scrub: 0.15,
            },
        });
    }

    // 2. Floating Back to Top Button
    const backToTopBtn = document.getElementById('btn-back-to-top');
    if (backToTopBtn) {
        ScrollTrigger.create({
            start: "top -250",
            onUpdate: (self) => {
                if (self.scroll() > 250) {
                    backToTopBtn.classList.add('is-visible');
                } else {
                    backToTopBtn.classList.remove('is-visible');
                }
            },
        });
    }

    // 3. Header Elevate on Scroll
    const header = document.querySelector('.public-header');
    if (header) {
        ScrollTrigger.create({
            start: "top -40",
            onUpdate: (self) => {
                if (self.scroll() > 40) {
                    header.style.boxShadow = "0 4px 20px rgba(0, 0, 0, 0.08)";
                } else {
                    header.style.boxShadow = "";
                }
            },
        });
    }

    // 4. Hero Content Subtle Entrance
    const heroInner = document.querySelector('.page-home .hero-inner');
    if (heroInner) {
        gsap.from(".page-home .hero-inner > *", {
            y: 18,
            duration: 0.7,
            stagger: 0.1,
            ease: "power2.out",
            clearProps: "all",
        });
    }

    // 5. Hero Parallax
    const homeHero = document.querySelector('.page-home .home-hero');
    if (homeHero) {
        gsap.to(homeHero, {
            backgroundPosition: "center 45%",
            ease: "none",
            scrollTrigger: {
                trigger: homeHero,
                start: "top top",
                end: "bottom top",
                scrub: 0.4,
            },
        });
    }

    // 6. Section Headers (Gentle lift, always visible)
    gsap.utils.toArray(".page-home .section-head, .page-home .info-dark-head, .page-home .contact-head-block").forEach(head => {
        gsap.from(head, {
            y: 18,
            duration: 0.5,
            ease: "power2.out",
            clearProps: "all",
            scrollTrigger: {
                trigger: head,
                start: "top 95%",
                once: true,
            },
        });
    });

    // 7. Pilihan Dusun (Padukuhan Cards: always visible, subtle entry lift)
    const dusunGrid = document.querySelector('.page-home .dusun-grid');
    if (dusunGrid) {
        gsap.from(".page-home .dusun-card", {
            y: 22,
            stagger: 0.06,
            duration: 0.55,
            ease: "power2.out",
            clearProps: "all",
            scrollTrigger: {
                trigger: dusunGrid,
                start: "top 95%",
                once: true,
            },
        });
    }

    // 8. Informasi Desa (3 Horizontal Cards: always visible)
    const infoGrid = document.querySelector('.page-home .info-horizontal-grid');
    if (infoGrid) {
        gsap.from(".page-home .info-h-card", {
            y: 22,
            stagger: 0.08,
            duration: 0.55,
            ease: "power2.out",
            clearProps: "all",
            scrollTrigger: {
                trigger: infoGrid,
                start: "top 95%",
                once: true,
            },
        });
    }

    // 9. Warta & Agenda Items (Always visible)
    const pengumumanList = document.querySelector('#pengumuman .update-list');
    if (pengumumanList) {
        gsap.from("#pengumuman .update-item", {
            y: 18,
            stagger: 0.07,
            duration: 0.5,
            ease: "power2.out",
            clearProps: "all",
            scrollTrigger: {
                trigger: pengumumanList,
                start: "top 95%",
                once: true,
            },
        });
    }

    const agendaList = document.querySelector('#agenda .update-list');
    if (agendaList) {
        gsap.from("#agenda .update-item", {
            y: 18,
            stagger: 0.07,
            duration: 0.5,
            ease: "power2.out",
            clearProps: "all",
            scrollTrigger: {
                trigger: agendaList,
                start: "top 95%",
                once: true,
            },
        });
    }

    // 10. Kontak Desa Cards (Always visible)
    const contactGrid = document.querySelector('.page-home .home-contact-grid');
    if (contactGrid) {
        gsap.from(".page-home .home-contact-card", {
            y: 20,
            stagger: 0.07,
            duration: 0.5,
            ease: "power2.out",
            clearProps: "all",
            scrollTrigger: {
                trigger: contactGrid,
                start: "top 95%",
                once: true,
            },
        });
    }

    // 11. ScrollSpy: Update active nav link
    const sectionIds = ['beranda', 'dusun', 'informasi-desa', 'pengumuman', 'agenda', 'peta-desa', 'kontak-desa'];
    sectionIds.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        ScrollTrigger.create({
            trigger: el,
            start: "top 45%",
            end: "bottom 45%",
            onToggle: self => {
                if (self.isActive) {
                    document.querySelectorAll('.public-nav-link').forEach(link => {
                        const href = link.getAttribute('href');
                        if (href && (href.endsWith('#' + id) || href === '#' + id)) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                }
            },
        });
    });

    ScrollTrigger.refresh();
}

// Progressive enhancement: mobile nav toggle and initializations
document.addEventListener('DOMContentLoaded', () => {
    initDashboardCharts();
    initAiAssistant();

    const toggle = document.getElementById('mobile-menu-toggle');
    const nav = document.getElementById('mobile-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // Scroll reveal: subtle, respects reduced motion
    const revealEls = document.querySelectorAll('[data-reveal]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!reduceMotion && revealEls.length > 0 && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -48px 0px' });

        revealEls.forEach((el) => io.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-revealed'));
    }

    initScrollAnimations();
});

window.addEventListener('load', () => {
    if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.refresh();
    }
});
