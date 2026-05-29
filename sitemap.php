<?php
header('Content-Type: application/xml; charset=utf-8');
$base = (isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc><?= $base ?>/index.php</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
  <url><loc><?= $base ?>/index.php?page=pharmacie</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/index.php?page=medicament</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/index.php?page=blog</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
  <url><loc><?= $base ?>/index.php?page=contact</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
</urlset>
