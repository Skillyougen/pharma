  </div><!-- /.a-content -->
</main>

<div id="a-overlay" onclick="toggleAdminNav()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:99"></div>

<script>
function toggleAdminNav(){
  const s=document.getElementById('sidebar');
  const o=document.getElementById('a-overlay');
  const open=s.classList.toggle('open');
  o.style.display=open?'block':'none';
}
// Show burger on mobile
if(window.innerWidth<=768){
  const b=document.getElementById('a-burger');
  if(b) b.style.display='block';
}
// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el=>{
  el.addEventListener('click',function(e){
    if(!confirm(this.dataset.confirm||'Confirmer la suppression ?')) e.preventDefault();
  });
});
// Auto-dismiss alerts after 5s
setTimeout(()=>{
  document.querySelectorAll('.a-alert').forEach(a=>{
    a.style.transition='opacity .4s';
    a.style.opacity='0';
    setTimeout(()=>a.remove(),400);
  });
},5000);
</script>
</body>
</html>
