// Part 13.1 — Toast System
function showToast(message, type = 'error', duration = 4000) {
  const toast = document.createElement('div');
  toast.className = `toast toast--${type}`;
  toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
  toast.textContent = message;
  
  // Basic inline styles for the toast if not in CSS
  Object.assign(toast.style, {
    position: 'fixed',
    bottom: '40px',
    left: '50%',
    transform: 'translateX(-50%)',
    background: type === 'error' ? '#9a5a52' : '#c4847a',
    color: 'white',
    padding: '12px 24px',
    borderRadius: '30px',
    zIndex: '9999',
    fontFamily: 'EB Garamond, serif',
    fontSize: '16px',
    boxShadow: '0 4px 20px rgba(0,0,0,0.15)'
  });

  document.body.appendChild(toast);
  
  gsap.from(toast, { y: 20, opacity: 0, duration: 0.3 });
  
  setTimeout(() => {
    gsap.to(toast, { y: -10, opacity: 0, duration: 0.3, onComplete: () => toast.remove() });
  }, duration);
}

// 1.6 Storage Helpers
const Storage = {
  save(key, val, session = false) {
    const store = session ? sessionStorage : localStorage;
    store.setItem(`ts_${key}`, JSON.stringify(val));
  },
  get(key, session = false) {
    const store = session ? sessionStorage : localStorage;
    const val = store.getItem(`ts_${key}`);
    return val ? JSON.parse(val) : null;
  }
};

window.showToast = showToast;
window.Storage = Storage;
