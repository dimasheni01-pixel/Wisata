document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav-links');
  if (toggle && nav) {
    const closeNav = () => {
      nav.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Buka navigasi');
    };
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Tutup navigasi' : 'Buka navigasi');
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && nav.classList.contains('open')) {
        closeNav();
        toggle.focus();
      }
    });
    document.addEventListener('click', event => {
      if (!event.target.closest('.site-header')) closeNav();
    });
    nav.addEventListener('click', event => {
      if (event.target.closest('a')) closeNav();
    });
    window.matchMedia('(max-width: 960px)').addEventListener('change', closeNav);
  }
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', event => {
      if (!confirm(el.getAttribute('data-confirm') || 'Yakin melanjutkan?')) event.preventDefault();
    });
  });
  document.querySelectorAll('[data-preview]').forEach(input => {
    input.addEventListener('change', () => {
      const target = document.querySelector(input.dataset.preview);
      if (target && input.files && input.files[0]) target.src = URL.createObjectURL(input.files[0]);
    });
  });
});
