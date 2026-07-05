gsap.registerPlugin(ScrollTrigger, Flip);

// 1. Lenis Smooth Scroll
const lenis = new Lenis({
    lerp: 0.1,
    smooth: true,
    smoothTouch: false
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

// 2. Application State Object (Part 12.1)
window.TRENDSHIP = {
    currentPage: 'home',
    activeFilter: localStorage.getItem('ts_preferred_filter') || 'all',
    moodboard: JSON.parse(sessionStorage.getItem('ts_moodboard')) || { items: [], name: '' },
    navOpen: false,
    lastScrollY: 0,
    threeInstances: [],
    activeTheme: null,

    // Methods
    addToMoodboard(id) {
        if (this.moodboard.items.length >= 6) return;
        if (this.moodboard.items.includes(id)) return;
        this.moodboard.items.push(id);
        sessionStorage.setItem('ts_moodboard', JSON.stringify(this.moodboard));
        if (window.updateMoodboardUI) window.updateMoodboardUI();
    },
    removeFromMoodboard(id) {
        this.moodboard.items = this.moodboard.items.filter(item => item !== id);
        sessionStorage.setItem('ts_moodboard', JSON.stringify(this.moodboard));
        if (window.updateMoodboardUI) window.updateMoodboardUI();
    },
    openNav() {
        this.navOpen = true;
        document.body.classList.add('nav-open');
    },
    closeNav() {
        this.navOpen = false;
        document.body.classList.remove('nav-open');
    }
};

// 3. Three.js Memory Management (Part 12.2)
function disposeThreeInstances() {
    window.TRENDSHIP.threeInstances.forEach(({ renderer, scene }) => {
        if (scene) {
            scene.traverse(obj => {
                if (obj.geometry) obj.geometry.dispose();
                if (obj.material) {
                    if (Array.isArray(obj.material)) obj.material.forEach(m => m.dispose());
                    else obj.material.dispose();
                }
            });
        }
        if (renderer) {
            renderer.dispose();
            if (renderer.domElement && renderer.domElement.parentNode) {
                renderer.domElement.parentNode.removeChild(renderer.domElement);
            }
        }
    });
    window.TRENDSHIP.threeInstances = [];
}

// 4. Custom Cursor
const initCursor = () => {
    const cursor = document.querySelector('.cursor');
    if (!cursor) return;

    window.addEventListener('mousemove', (e) => {
        gsap.to(cursor, {
            x: e.clientX,
            y: e.clientY,
            duration: 0.1,
            ease: "power2.out"
        });
    });

    const addHoverListeners = () => {
        document.querySelectorAll('a, button, .slot-item, .journal-card, .material-card, .photo-card').forEach(el => {
            el.addEventListener('mouseenter', () => cursor.classList.add('hover'));
            el.addEventListener('mouseleave', () => cursor.classList.remove('hover'));
        });
    };
    addHoverListeners();
};

// 5. Page Transitions (Part 7.1)
const pageTransition = {
    circle: document.querySelector('.transition-circle'),
    
    in() {
        return gsap.to(this.circle, {
            scale: 3000,
            duration: 1.0,
            ease: "power2.in"
        });
    },
    
    out() {
        return gsap.to(this.circle, {
            scale: 0,
            duration: 0.85,
            ease: "power2.out",
            delay: 0.05
        });
    }
};

// 6. Barba.js Initialization
barba.init({
    transitions: [{
        name: 'circle-wipe',
        async leave(data) {
            await pageTransition.in();
            data.current.container.remove();
        },
        async enter(data) {
            await pageTransition.out();
        }
    }],
    views: [
        { namespace: 'home', afterEnter() { if (window.initHome) window.initHome(); } },
        { namespace: 'design-trend', afterEnter() { if (window.initDesignTrend) window.initDesignTrend(); } },
        { namespace: 'themes', afterEnter() { if (window.initThemes) window.initThemes(); } },
        { namespace: 'exhibition', afterEnter() { if (window.initExhibition) window.initExhibition(); } },
        { namespace: 'collection', afterEnter() { if (window.initCollection) window.initCollection(); } },
        { namespace: 'trend-report', afterEnter() { if (window.initTrendReport) window.initTrendReport(); } },
        { namespace: 'lookbook', afterEnter() { if (window.initLookbook) window.initLookbook(); } },
        { namespace: 'about', afterEnter() { if (window.initAbout) window.initAbout(); } },
        { namespace: 'contact', afterEnter() { if (window.initContact) window.initContact(); } }
    ]
});

barba.hooks.beforeLeave(() => {
    disposeThreeInstances();
});

barba.hooks.afterEnter((data) => {
    window.TRENDSHIP.currentPage = data.next.namespace;
    lenis.scrollTo(0, { immediate: true });
    ScrollTrigger.getAll().forEach(t => t.kill());
    ScrollTrigger.refresh();
    initCursor();
    updateNavActiveState();
    
    // Split text for animations in new container
    data.next.container.querySelectorAll('.hero-wordmark').forEach(el => {
        el.innerHTML = el.innerText.split('').map(c => `<span class="char" style="display:inline-block">${c}</span>`).join('');
    });
});

function updateNavActiveState() {
    const nav = document.getElementById('main-nav');
    if (!nav) return;
    const currentPath = window.location.pathname;
    nav.querySelectorAll('.nav-link').forEach(l => {
        const href = l.getAttribute('href');
        if (href === '/' && (currentPath === '/' || currentPath === '/index.html')) {
            l.classList.add('active');
        } else if (href !== '/' && currentPath.startsWith(href)) {
            l.classList.add('active');
        } else {
            l.classList.remove('active');
        }
    });
}

// 7. Nav Scroll Blur
const initNav = () => {
    const nav = document.querySelector('nav');
    if (!nav) return;

    ScrollTrigger.create({
        start: "top -50",
        onEnter: () => nav.classList.add('scrolled'),
        onLeaveBack: () => nav.classList.remove('scrolled')
    });
};

// Component Injection Loader
const injectComponents = async () => {
    const navEl = document.getElementById('main-nav');
    const footerEl = document.getElementById('main-footer');

    if (navEl) {
        const res = await fetch('/components/nav.html');
        navEl.innerHTML = await res.text();
        updateNavActiveState();
    }

    if (footerEl) {
        const res = await fetch('/components/footer.html');
        footerEl.innerHTML = await res.text();
        
        // Newsletter listener (delegated)
        document.addEventListener('click', async (e) => {
            if (e.target && e.target.id === 'nl-submit') {
                const nlEmail = document.getElementById('nl-email');
                const email = nlEmail.value;
                if (!email || !email.includes('@')) {
                    alert('Please enter a valid email address.');
                    return;
                }
                
                e.target.innerText = '...';
                e.target.disabled = true;
                
                try {
                    const response = await window.TrendshipAPI.subscribeNewsletter(email);
                    if (response.success) {
                        document.getElementById('newsletter-form').innerHTML = `<p style="color: var(--rose); margin-top: 10px;">${response.message}</p>`;
                    } else {
                        alert(response.error || 'Subscription failed.');
                        e.target.innerText = 'SUBSCRIBE';
                        e.target.disabled = false;
                    }
                } catch (error) {
                    alert('Network error. Is the backend running?');
                    e.target.innerText = 'SUBSCRIBE';
                    e.target.disabled = false;
                }
            }
        });
    }
    
    initNav();
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    // Split text for animations
    document.querySelectorAll('.hero-wordmark').forEach(el => {
        el.innerHTML = el.innerText.split('').map(c => `<span class="char" style="display:inline-block">${c}</span>`).join('');
    });

    initCursor();
    injectComponents();
});

window.TRENDSHIP.lenis = lenis;
window.TRENDSHIP.pageTransition = pageTransition;

