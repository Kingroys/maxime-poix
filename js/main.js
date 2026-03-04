const sections = document.querySelectorAll('.section');
const links = document.querySelectorAll('#menu a');

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
    }
  });
}, { threshold: 0.16 });

sections.forEach((section) => revealObserver.observe(section));

const navObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (!entry.isIntersecting) return;
    links.forEach((link) => {
      const active = link.getAttribute('href') === `#${entry.target.id}`;
      link.classList.toggle('active', active);
    });
  });
}, { threshold: 0.45 });

sections.forEach((section) => navObserver.observe(section));

const logo = document.querySelector('.brand img');
if (logo) {
  window.addEventListener('mousemove', (event) => {
    const x = (event.clientX / window.innerWidth - 0.5) * 2;
    const y = (event.clientY / window.innerHeight - 0.5) * -2;
    logo.style.transform = `translate3d(0,0,0) rotateX(${y}deg) rotateY(${x}deg)`;
  });

  window.addEventListener('mouseleave', () => {
    logo.style.transform = 'rotateX(0deg) rotateY(0deg)';
  });
}
