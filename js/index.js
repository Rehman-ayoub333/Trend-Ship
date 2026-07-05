/**
 * TRENDSHIP - Home Page Animations
 */

document.addEventListener('DOMContentLoaded', () => {
  initHeroAnimations();
  initPhotoStrip();
  initSynergyAnimation();
  initThemePicker();
  initMaisonAnimation();
  initBuildingZoom();
  initAboutTicker();
});

/**
 * §1 Hero Animations
 */
function initHeroAnimations() {
  // Wordmark slide-up
  gsap.from('.hero-wordmark', {
    y: '100%',
    duration: 1.2,
    ease: "power3.out",
    delay: 0.5
  });

  // Background shift on scroll
  gsap.to('body', {
    backgroundColor: '#0A0A0A',
    scrollTrigger: {
      trigger: '#hero',
      start: 'top top',
      end: 'bottom top',
      scrub: true
    }
  });

  // Organism scale
  gsap.to('#organism-canvas', {
    scale: 1.5,
    opacity: 0,
    scrollTrigger: {
      trigger: '#hero',
      start: 'top top',
      end: 'bottom top',
      scrub: true
    }
  });
}

/**
 * §2 Photo Strip Parallax
 */
function initPhotoStrip() {
  gsap.to('.strip-track', {
    x: () => -(document.querySelector('.strip-track').offsetWidth - window.innerWidth),
    ease: "none",
    scrollTrigger: {
      trigger: '#photo-strip',
      start: 'top top',
      end: () => `+=${document.querySelector('.strip-track').offsetWidth}`,
      scrub: 1,
      pin: true,
      anticipatePin: 1
    }
  });

  // Card reveals
  gsap.from('.strip-card', {
    y: 100,
    opacity: 0,
    stagger: 0.1,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
      trigger: '#photo-strip',
      start: 'top 80%',
    }
  });
}

/**
 * §3 SY(E)NERGY Crash
 */
function initSynergyAnimation() {
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: '#synergy',
      start: 'top center',
      end: 'bottom center',
      toggleActions: 'play none none reverse'
    }
  });

  tl.from('.sy', { x: -200, opacity: 0, duration: 1, ease: "power4.out" })
    .from('.nergy', { x: 200, opacity: 0, duration: 1, ease: "power4.out" }, "<")
    .from('.e-box', { scale: 0, duration: 0.8, ease: "elastic.out(1, 0.5)" }, "-=0.5")
    .to('.tagline', { opacity: 1, y: 0, duration: 1 }, "-=0.3");
}

/**
 * §4 Theme Picker Slot Machine
 */
function initThemePicker() {
  const items = document.querySelectorAll('.picker-item');
  const track = document.querySelector('.picker-track');
  
  gsap.to(track, {
    y: () => -(track.offsetHeight - (window.innerWidth * 0.15)), // 15vw height
    ease: "none",
    scrollTrigger: {
      trigger: '#theme-picker',
      start: 'top top',
      end: 'bottom top',
      scrub: 1,
      pin: true,
      onUpdate: (self) => {
        const progress = self.progress;
        const index = Math.min(Math.floor(progress * items.length), items.length - 1);
        items.forEach((item, i) => {
          if (i === index) item.classList.add('active');
          else item.classList.remove('active');
        });
      }
    }
  });
}

/**
 * §5 Maison Reveal
 */
function initMaisonAnimation() {
  gsap.from('.maison-orb', {
    scale: 0.4,
    opacity: 0,
    duration: 2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: '#maison',
      start: 'top 60%',
    }
  });

  gsap.from('.maison-text', {
    x: -50,
    opacity: 0,
    duration: 1,
    scrollTrigger: {
      trigger: '#maison',
      start: 'top 50%',
    }
  });
}

/**
 * §6 Building Zoom
 */
function initBuildingZoom() {
  gsap.to('.ken-burns', {
    scale: 1.2,
    scrollTrigger: {
      trigger: '#building',
      start: 'top bottom',
      end: 'bottom top',
      scrub: true
    }
  });
}

/**
 * §7 About Ticker
 */
function initAboutTicker() {
  gsap.to('.ticker', {
    xPercent: -50,
    ease: "none",
    scrollTrigger: {
      trigger: '#about-landing',
      start: 'top bottom',
      end: 'bottom top',
      scrub: true
    }
  });

  // Counter parallax for images
  gsap.to('.img-1', {
    y: -100,
    scrollTrigger: {
      trigger: '#about-landing',
      scrub: true
    }
  });

  gsap.to('.img-2', {
    y: 100,
    scrollTrigger: {
      trigger: '#about-landing',
      scrub: true
    }
  });
}
