<?php
$dir = new RecursiveDirectoryIterator('c:/laragon/www/tokoa&r/resources/views/admin');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$search1 = 'class="text-sm text-pink-600 font-medium hover:underline">Kembali</a>';
$search2 = 'class="text-sm text-pink-600 hover:underline">Kembali</a>';

$replace = 'class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>';

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $new_content = str_replace([$search1, $search2], [$replace, $replace], $content);
    if ($content !== $new_content) {
        file_put_contents($path, $new_content);
        echo "Updated $path\n";
    }
}
