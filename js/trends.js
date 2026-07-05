/**
 * TRENDSHIP - Trends Page Animations
 */

document.addEventListener('DOMContentLoaded', () => {
  initThemeChapters();
  initProgressBar();
});

function initThemeChapters() {
  const chapters = document.querySelectorAll('.theme-chapter');

  chapters.forEach((chapter, i) => {
    const collage = chapter.querySelector('.chapter-collage');
    const images = collage.querySelectorAll('.collage-img');
    const title = chapter.querySelector('h1');
    const info = chapter.querySelector('.chapter-info');

    // Title reveal
    gsap.from(title, {
      scale: 0.5,
      opacity: 0,
      duration: 1.5,
      ease: "elastic.out(1, 0.7)",
      scrollTrigger: {
        trigger: chapter,
        start: 'top center',
      }
    });

    // Collage parallax
    images.forEach((img, j) => {
      gsap.from(img, {
        y: 100 * (j + 1),
        opacity: 0,
        duration: 1.2,
        ease: "power3.out",
        scrollTrigger: {
          trigger: chapter,
          start: 'top 70%',
        }
      });

      // Continuous parallax on scroll
      gsap.to(img, {
        y: (j + 1) * -40,
        scrollTrigger: {
          trigger: chapter,
          start: 'top bottom',
          end: 'bottom top',
          scrub: 1.5
        }
      });
    });

    // Section Snap
    ScrollTrigger.create({
      trigger: chapter,
      start: 'top top',
      end: 'bottom top',
      pin: true,
      pinSpacing: false,
      snap: 1
    });
  });
}

function initProgressBar() {
  const chapters = document.querySelectorAll('.theme-chapter');
  const segments = document.querySelectorAll('.progress-segment');

  chapters.forEach((chapter, i) => {
    ScrollTrigger.create({
      trigger: chapter,
      start: 'top center',
      end: 'bottom center',
      onEnter: () => updateProgress(i, true),
      onEnterBack: () => updateProgress(i, true),
      onLeave: () => updateProgress(i, false),
      onLeaveBack: () => updateProgress(i, false)
    });

    // Detailed progress fill
    gsap.to(`.progress-segment:nth-child(${i+1})`, {
      scrollTrigger: {
        trigger: chapter,
        start: 'top bottom',
        end: 'bottom top',
        onUpdate: (self) => {
          const progress = self.progress;
          const segment = segments[i];
          // We need a way to fill the segment, maybe a child element
          gsap.set(segment, { "--p": `${progress * 100}%` });
        }
      }
    });
  });
}

function updateProgress(index, active) {
  const segments = document.querySelectorAll('.progress-segment');
  // Logic to fill segments up to index
}
