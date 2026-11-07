document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav-links');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(open));
    });
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
