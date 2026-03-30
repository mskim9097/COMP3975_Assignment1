<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <h1 class="d-flex justify-content-between align-items-center">
            <span>Admin - Mini CMS</span>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
    @csrf
    <button type="submit" class="btn btn-secondary">Logout</button>
</form>
        </h1>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <h4>Articles</h4>
                <ul class="list-group mb-3" id="articleList">
                    @foreach ($articles as $article)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $article->id }}">
                        <span class="title">{{ $article->title }}</span>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteArticle(event, {{ $article->id }})">delete</button>
                    </li>
                    @endforeach
                </ul>
                <button class="btn btn-secondary w-100" onclick="newArticle()">New Article</button>
            </div>
            <div class="col-md-9">
                <input type="text" id="title" class="form-control mb-3" placeholder="Article Title">
                <div id="editor"></div>
                <button class="btn btn-secondary mt-3" onclick="saveArticle()">Save</button>
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
            fetch('/api/articles/' + id)
                .then(r => r.json())
                .then(data => {
                    if (data) {
                        currentId = id;
                        document.getElementById('title').value = data.title;
                        quill.root.innerHTML = data.content;
                        updateActiveState(id);
                    }
                });
        }

        function saveArticle() {
            var title = document.getElementById('title').value;
            var content = quill.root.innerHTML;
            if (!title) { alert('Title required'); return; }

            var method = currentId ? 'PUT' : 'POST';
            var url = '/api/articles' + (currentId ? '/' + currentId : '');

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
            fetch('/api/articles/' + id, { method: 'DELETE' })
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
                li.classList.toggle('bg-secondary-subtle', li.dataset.id == id);
                li.classList.toggle('active', li.dataset.id == id);
            });
        }

        document.getElementById('articleList').addEventListener('click', function(e) {
            var li = e.target.closest('li');
            if (li && !e.target.classList.contains('btn')) {
                loadArticle(li.dataset.id);
            }
        });
    </script>
</body>
</html>
