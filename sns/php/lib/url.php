<?php
//URLの文字列をリンクにする
function url_to_anchor($text) {
$pattern = '(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)';
$replacement = '<a href="\1" target="_blank">\1</a>';
$text = mb_ereg_replace($pattern, $replacement, htmlspecialchars($text));

return $text;
}