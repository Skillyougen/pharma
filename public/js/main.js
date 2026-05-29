// PharmaLink — main.js
document.addEventListener('DOMContentLoaded', function() {
  // Flash messages auto-dismiss
  document.querySelectorAll('.flash').forEach(function(el) {
    setTimeout(function() { el.style.transition='opacity .5s'; el.style.opacity='0'; setTimeout(function(){el.remove();},500); }, 5000);
  });

  // Scroll to top
  var btn = document.getElementById('return-to-top');
  if (btn) {
    window.addEventListener('scroll', function() {
      btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
    btn.addEventListener('click', function(e) { e.preventDefault(); window.scrollTo({top:0,behavior:'smooth'}); });
  }
});

function toggleNav() {
  var nav = document.getElementById('navLinks');
  if (nav) nav.classList.toggle('open');
}
