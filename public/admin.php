<?php
header("Content-Type: text/html; charset=UTF-8");
require_once __DIR__ . '/../src/System/DatabaseConnector.php';
use Src\System\DatabaseConnector;

$articles = [];
try {
    $dbConnection = (new DatabaseConnector())->getConnection();
    $stmt = $dbConnection->query("SELECT id, title, created_at FROM articles ORDER BY created_at DESC");
    $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin - Mini CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        #editor { height: 300px; }
        .list-group-item.active { background-color: #0d6efd; border-color: #0d6efd; }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1>Admin - Mini CMS</h1>
        <p><a href="/">Back to Home</a></p>
        <div class="row">
            <div class="col-md-3">
                <h4>Articles</h4>
                <ul class="list-group mb-3" id="articleList">
                    <?php foreach ($articles as $article): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $article['id'] ?>">
                        <span class="title"><?= htmlspecialchars($article['title']) ?></span>
                        <button class="btn btn-sm btn-danger" onclick="deleteArticle(event, <?= $article['id'] ?>)">X</button>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <button class="btn btn-secondary w-100" onclick="newArticle()">New Article</button>
            </div>
            <div class="col-md-9">
                <input type="text" id="title" class="form-control mb-3" placeholder="Article Title">
                <div id="editor"></div>
                <button class="btn btn-primary mt-3" onclick="saveArticle()">Save</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: { toolbar: true }
        });
        var currentId = null;

        function loadArticle(id) {
            fetch('/articles/' + id)
                .then(r => r.json())
                .then(data => {
                    if (data && data[0]) {
                        currentId = id;
                        document.getElementById('title').value = data[0].title;
                        quill.root.innerHTML = data[0].content;
                        updateActiveState(id);
                    }
                });
        }

        function saveArticle() {
            var title = document.getElementById('title').value;
            var content = quill.root.innerHTML;
            if (!title) { alert('Title required'); return; }

            var method = currentId ? 'PUT' : 'POST';
            var url = currentId ? '/articles/' + currentId : '/articles';

            fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title: title, content: content })
            }).then(r => {
                if (r.ok) location.reload();
            });
        }

        function deleteArticle(e, id) {
            e.stopPropagation();
            if (!confirm('Delete this article?')) return;
            fetch('/articles/' + id, { method: 'DELETE' })
                .then(r => { if (r.ok) location.reload(); });
        }

        function newArticle() {
            currentId = null;
            document.getElementById('title').value = '';
            quill.setText('');
            updateActiveState(null);
        }

        function updateActiveState(id) {
            document.querySelectorAll('#articleList li').forEach(li => {
                li.classList.toggle('active', li.dataset.id == id);
            });
        }

        document.getElementById('articleList').addEventListener('click', function(e) {
            var li = e.target.closest('li');
            if (li && !e.target.classList.contains('btn-danger')) {
                loadArticle(li.dataset.id);
            }
        });
    </script>
</body>
</html>
