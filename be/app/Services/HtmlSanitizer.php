<?php

namespace App\Services;

/**
 * Sanitasi HTML dari editor (Summernote/dll) sebelum disimpan/ditampilkan.
 *
 * Allowlist berbasis DOM (bukan regex): hanya tag/atribut yang dikenal aman
 * yang dipertahankan. Menangani bypass regex klasik: entitas HTML, whitespace/
 * kontrol di nama atribut, quote tak seimbang, nested tag, dan skema URL
 * berbahaya (javascript:/vbscript:/data: kecuali gambar).
 */
class HtmlSanitizer
{
    /** Tag yang boleh dipertahankan (tanpa anak executable). */
    private const ALLOWED_TAGS = [
        'p', 'br', 'b', 'strong', 'i', 'em', 'u', 's',
        'h2', 'h3', 'h4', 'blockquote', 'pre', 'code',
        'ul', 'ol', 'li', 'hr', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'a', 'img',
    ];

    /** Atribut yang boleh per tag (huruf kecil). */
    private const ALLOWED_ATTR = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'loading'],
        'th' => ['colspan', 'rowspan'],
        'td' => ['colspan', 'rowspan'],
    ];

    public static function clean(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        // Batasi ukuran untuk mencegah DoS parser.
        if (strlen($html) > 500_000) {
            $html = substr($html, 0, 500_000);
        }

        $prev = libxml_use_internal_errors(true);
        try {
            $doc = new \DOMDocument('1.0', 'UTF-8');
            // Bungkus agar fragment selalu punya satu root.
            $wrapped = '<div id="__root__">' . $html . '</div>';
            $doc->loadHTML(
                '<?xml encoding="utf-8" ?>' . $wrapped,
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING
            );

            $xpath = new \DOMXPath($doc);

            // 1. Buang komentar (dapat menyembunyikan markup / conditional IE).
            foreach ($xpath->query('//comment()') as $comment) {
                $comment->parentNode?->removeChild($comment);
            }

            // 2. Buang tag berbahaya beserta isinya.
            foreach ($xpath->query('//*[translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "script" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "style" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "iframe" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "object" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "embed" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "form" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "input" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "button" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "select" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "option" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "textarea" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "svg" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "math" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "link" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "meta" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "base" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "title" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "video" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "audio" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "source" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "track" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "picture" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "canvas" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "noscript" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "template" or translate(local-name(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz") = "slot"]') as $bad) {
                $bad->parentNode?->removeChild($bad);
            }

            $root = $doc->getElementById('__root__');
            if (! $root) {
                return '';
            }

            self::sanitizeNode($root);

            $out = '';
            foreach ($root->childNodes as $child) {
                $out .= $doc->saveHTML($child);
            }

            return $out;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($prev);
        }
    }

    private static function sanitizeNode(\DOMNode $node): void
    {
        // Iterasi mundur agar penghapusan aman.
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if ($child instanceof \DOMElement) {
                $tag = strtolower($child->tagName);

                if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    // Unwrap: pertahankan anak teks/elemen aman, buang bungkusnya.
                    // Elemen dengan perilaku aktif (details, marquee, dll) ikut dibuang bungkusnya.
                    $frag = $child->ownerDocument->createDocumentFragment();
                    while ($child->firstChild) {
                        $frag->appendChild($child->firstChild);
                    }
                    $node->replaceChild($frag, $child);
                    // Sanitasi ulang hasil unwrap.
                    self::sanitizeNode($frag);
                    continue;
                }

                self::sanitizeAttributes($child, $tag);
                self::sanitizeNode($child);

                // Paksa tautan aman dibuka di tab baru tanpa akses window.opener.
                if ($tag === 'a') {
                    $child->setAttribute('target', '_blank');
                    $child->setAttribute('rel', 'noopener noreferrer');
                }
                if ($tag === 'img') {
                    if (! $child->hasAttribute('alt')) {
                        $child->setAttribute('alt', '');
                    }
                    $child->setAttribute('loading', 'lazy');
                }
            } elseif ($child instanceof \DOMComment) {
                $node->removeChild($child);
            }
        }
    }

    private static function sanitizeAttributes(\DOMElement $el, string $tag): void
    {
        $allowed = self::ALLOWED_ATTR[$tag] ?? [];
        // Salin dulu karena kita menghapus saat iterasi.
        $attrs = iterator_to_array($el->attributes);
        foreach ($attrs as $attr) {
            $name = strtolower(trim($attr->nodeName));
            // Tolak event handler (termasuk varian dengan entitas/whitespace
            // yang sudah dinormalisasi DOM menjadi nama atribut).
            if (str_starts_with($name, 'on')) {
                $el->removeAttribute($attr->nodeName);
                continue;
            }
            if ($name === 'style' || $name === 'srcset') {
                $el->removeAttribute($attr->nodeName);
                continue;
            }
            if (! in_array($name, $allowed, true)) {
                $el->removeAttribute($attr->nodeName);
                continue;
            }
            $value = trim($attr->nodeValue ?? '');
            if (strlen($value) > 2048 || preg_match('/[\x00-\x1F\x7F]/', $value)) {
                $el->removeAttribute($attr->nodeName);
                continue;
            }
            if (in_array($name, ['href', 'src'], true) && ! self::isSafeUrl($value, $tag === 'img')) {
                $el->removeAttribute($attr->nodeName);
            }
        }
    }

    private static function isSafeUrl(string $url, bool $isImg): bool
    {
        if ($url === '' || str_starts_with($url, '#')) {
            return $url !== '';
        }
        // Tolak protokol-relatif (//evil) dan backslash trick.
        if (str_starts_with($url, '//') || str_starts_with($url, '\\\\')) {
            return false;
        }
        $lower = strtolower(ltrim($url));
        // Decode entitas umum yang dipakai untuk menyamarkan skema.
        $lower = html_entity_decode($lower, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $lower = ltrim($lower);
        foreach (['javascript:', 'vbscript:', 'data:text/html', 'blob:', 'filesystem:'] as $bad) {
            if (str_starts_with($lower, $bad)) {
                return false;
            }
        }
        if (str_starts_with($lower, 'data:')) {
            return $isImg && (bool) preg_match('#^data:image/(png|jpeg|gif|webp);base64,#i', $lower);
        }
        return (bool) preg_match('#^(https?://[^/\s]+/?.*|/[^/\s\\\\][^\s\\\\]*)$#i', $url);
    }
}
