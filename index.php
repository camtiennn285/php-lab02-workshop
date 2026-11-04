<?php
$workshops = require __DIR__ . '/data/workshops.php';

$query = trim((string)($_GET['q'] ?? ''));
$category = (string)($_GET['category'] ?? 'all');
$allowedCategories = ['all', 'Web', 'AI', 'Design'];

if (!in_array($category, $allowedCategories, true)) $category = 'all';

$results = array_filter($workshops, static function(array $w) use ($query, $category): bool {
    return ($query === '' || stripos($w['title'], $query) !== false)
        && ($category === 'all' || $w['category'] === $category);
});
?>

<form action="index.php" method="get">
    <input name="q" placeholder="Search title" value="<?= htmlspecialchars($query) ?>">
    <select name="category">
        <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All</option>
        <option value="Web" <?= $category === 'Web' ? 'selected' : '' ?>>Web</option>
        <option value="AI" <?= $category === 'AI' ? 'selected' : '' ?>>AI</option>
        <option value="Design" <?= $category === 'Design' ? 'selected' : '' ?>>Design</option>
    </select>
    <button>Search</button>
</form>

<?php foreach ($results as $item): ?>
    <p><?= htmlspecialchars($item['title']) ?></p>
<?php endforeach; ?>

<?php if (empty($results)): ?>
    <p>No workshops matched your search.</p>
<?php endif; ?>