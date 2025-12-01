<?php
/**
 * Shared dashboard shell end: closes the layout wrapper and prints shared scripts.
 */
$jsVersion = '20241115';
$pageScripts = $pageScripts ?? [];
$scriptUrls = array_merge([
    URLROOT . "/public/js/script.js?v={$jsVersion}",
], $pageScripts);
$scriptUrls = array_values(array_unique($scriptUrls));
?>
  </div>
<?php
if (!empty($afterContainerInclude)) {
    $includes = is_array($afterContainerInclude) ? $afterContainerInclude : [$afterContainerInclude];
    foreach ($includes as $includePath) {
        if ($includePath) {
            require $includePath;
        }
    }
}
?>
<?php foreach ($scriptUrls as $src) : ?>
  <script src="<?php echo $src; ?>"></script>
<?php endforeach; ?>
<?php
if (!empty($inlineScripts) && is_array($inlineScripts)) {
    foreach ($inlineScripts as $inlineScript) {
        echo "  <script>{$inlineScript}</script>\n";
    }
}
?>
</body>
</html>
