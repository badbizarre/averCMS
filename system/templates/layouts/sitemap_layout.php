<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\r\n"; ?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php foreach ($pages as $page): ?>
  <url>
    <loc>http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $page['path']; ?></loc>
  </url>
<?php endforeach; ?>
<?php foreach ($trees as $tree): ?>
  <url>
    <loc>http://<?php echo $_SERVER['HTTP_HOST'] . '/recepty/' . $tree['path']; ?></loc>
  </url>
<?php endforeach; ?>
<?php foreach ($products as $product): ?>
  <url>
    <loc>http://<?php echo $_SERVER['HTTP_HOST'] . '/recept/' . $product['path']; ?></loc>
  </url>
<?php endforeach; ?>
</urlset>