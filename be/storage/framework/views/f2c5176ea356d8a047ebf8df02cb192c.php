<!doctype html>

<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($title); ?></title>
<meta name="description" content="<?php echo e($description); ?>">
<meta name="robots" content="<?php echo e($robots ?? 'index, follow'); ?>">
<link rel="canonical" href="<?php echo e($canonical); ?>">
<meta property="og:type" content="agenda">
<meta property="og:site_name" content="<?php echo e($siteName); ?>">
<meta property="og:locale" content="id_ID">
<meta property="og:url" content="<?php echo e($canonical); ?>">
<meta property="og:title" content="<?php echo e($title); ?>">
<meta property="og:description" content="<?php echo e($description); ?>">
<meta property="og:image" content="<?php echo e($image); ?>">
<meta property="og:image:alt" content="<?php echo e($title); ?>">
<?php if($publishedAt): ?>
<meta property="agenda:published_time" content="<?php echo e($publishedAt); ?>">
<?php endif; ?>
<?php if($section): ?>
<meta property="agenda:section" content="<?php echo e($section); ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo e($title); ?>">
<meta name="twitter:description" content="<?php echo e($description); ?>">
<meta name="twitter:image" content="<?php echo e($image); ?>">
<meta name="twitter:image:alt" content="<?php echo e($title); ?>">
<script type="application/ld+json"><?php echo $jsonLd; ?></script>

<script async src="https://news.google.com/swg/js/v1/swg-basic.js"></script>
<script>
  (self.SWG_BASIC = self.SWG_BASIC || []).push(function (basicSubscriptions) {
    basicSubscriptions.init({
      type: "NewsArticle",
      isPartOfType: ["Product"],
      isPartOfProductId: "CAowmf_HDA:openaccess",
      clientOptions: { theme: "light", lang: "id" },
    });
  });
</script>
<style>body{font-family:system-ui,Arial,sans-serif;max-width:720px;margin:2rem auto;padding:0 1rem;color:#222}img{max-width:100%;height:auto}</style>
</head>
<body>
<main>
<p><a href="/">Beranda</a> / <a href="/agenda">Agenda</a></p>
<h1><?php echo e($heading); ?></h1>
<p><time datetime="<?php echo e($publishedAt ?? ''); ?>"><?php echo e($dateLabel); ?></time><?php if($author): ?> &bull; Oleh <?php echo e($author); ?><?php endif; ?></p>
<?php if($image): ?>
<img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>">
<?php endif; ?>
<?php $__empty_2 = true; $__currentLoopData = $paragraphs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
<p><?php echo e($paragraph); ?></p>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
<p><?php echo e($description); ?></p>
<?php endif; ?>
<p><a href="<?php echo e($canonical); ?>">Baca selengkapnya</a></p>
</main>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\seo\agenda.blade.php ENDPATH**/ ?>