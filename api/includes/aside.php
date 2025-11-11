<?php
// Accept either $pageKey (string) or $pageId (int). Set one before include.
$pageKey = isset($pageKey) ? (string) $pageKey : null;
$pageId = isset($pageId) ? (int) $pageId : null;

$json = file_get_contents(__DIR__ . '/../data/aside.json');
$data = json_decode($json, true);
$page = null;

if (!empty($data['pages'])) {
    // Try by key/slug first
    if ($pageKey !== null) {
        foreach ($data['pages'] as $p) {
            if (isset($p['key']) && $p['key'] === $pageKey) {
                $page = $p;
                break;
            }
        }
    }
    // fallback to numeric id
    if (!$page && $pageId !== null) {
        foreach ($data['pages'] as $p) {
            if (isset($p['id']) && (int) $p['id'] === $pageId) {
                $page = $p;
                break;
            }
        }
    }
    if (!$page)
        $page = $data['pages'][0];
}
?>
<script src="/api/scripts/aside.js"></script>
<aside id="aside" class="aside-wrapper" role="complementary">

    <input type="checkbox" id="aside-toggle" class="aside-toggle" aria-label="Toggle zijmenu">

    <div class="aside-container">
        <label for="aside-toggle" class="aside-logo" id="aside-logo">
            <img src="/api/images/logo-color.svg" alt="Yerothia logo">
        </label>

        <div class="aside-content">
            <ul class="aside-content-list" id="aside-content-list">
                <?php if (!empty($page['items'])): ?>
                    <?php foreach ($page['items'] as $it): ?>
                        <li data-target="<?= htmlspecialchars($it['elementId'] ?? '') ?>" class="aside-content-list-item">
                            <span class="aside-content-list-item-icon"><?= htmlspecialchars($it['icon'] ?? '') ?></span>
                            <span class="aside-content-list-item-text"><?= htmlspecialchars($it['caption'] ?? '') ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</aside>