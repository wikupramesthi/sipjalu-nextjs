<!doctype html>
{{-- Snapshot SEO server-side untuk crawler share (WhatsApp/Facebook/X/Telegram).
     Crawler tidak menjalankan JS, jadi meta OG/Twitter harus sudah ada di HTML
     mentah. Rute ini HANYA disajikan ke bot via nginx (manusia tetap ke SPA).
     Sengaja TIDAK menambah views (lihat SeoSnapshotController). --}}
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots ?? 'index, follow' }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:type" content="agenda">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:alt" content="{{ $title }}">
@if($publishedAt)
<meta property="agenda:published_time" content="{{ $publishedAt }}">
@endif
@if($section)
<meta property="agenda:section" content="{{ $section }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<script type="application/ld+json">{!! $jsonLd !!}</script>
{{-- Verifikasi Google News Publisher / SWG: snapshot ini yang disajikan nginx
  ke bot (termasuk verifikator Google), jadi script harus ada di sini juga. --}}
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
<h1>{{ $heading }}</h1>
<p><time datetime="{{ $publishedAt ?? '' }}">{{ $dateLabel }}</time>@if($author) &bull; Oleh {{ $author }}@endif</p>
@if($image)
<img src="{{ $image }}" alt="{{ $title }}">
@endif
@forelse($paragraphs as $paragraph)
<p>{{ $paragraph }}</p>
@empty
<p>{{ $description }}</p>
@endforelse
<p><a href="{{ $canonical }}">Baca selengkapnya</a></p>
</main>
</body>
</html>
