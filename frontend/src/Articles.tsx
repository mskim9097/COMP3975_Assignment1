import { useState, useEffect, useCallback } from 'react';
import type { Article } from './types';
import { fetchArticles, fetchArticle } from './api';

interface ArticleDetailProps {
  id: number;
  onBack: () => void;
}

interface ArticleFormProps {
  id?: number;
  onCancel: () => void;
}

function ArticleDetail({ id, onBack }: ArticleDetailProps) {
  const [article, setArticle] = useState<Article | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchArticle(id)
      .then(setArticle)
      .catch(() => setArticle(null))
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) return <div className="text-center py-4"><div className="spinner-border" role="status"><span className="visually-hidden">Loading...</span></div></div>;
  if (!article) return <div className="alert alert-danger">Article not found.</div>;

  return (
    <article className="article-detail mt-5">
      <h1>{article.title}</h1>
      <p className="article-date">{new Date(article.created_at).toLocaleDateString()}</p>
      <hr />
      <div className="article-content">{article.content}</div>
      <div className="article-nav">
        <button className="btn btn-outline-secondary" onClick={onBack}>Back to Articles</button>
      </div>
    </article>
  );
}

function ArticleForm({ id, onCancel }: ArticleFormProps) {
  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [loading, setLoading] = useState(id ? true : false);

  useEffect(() => {
    if (!id) return;
    fetchArticle(id)
      .then((article) => {
        setTitle(article.title);
        setContent(article.content);
      })
      .catch(() => onCancel())
      .finally(() => setLoading(false));
  }, [id, onCancel]);

  if (loading) return <div className="text-center py-4"><div className="spinner-border" role="status"><span className="visually-hidden">Loading...</span></div></div>;

  return (
    <form className="article-form">
      <h2 className="mb-3">{id ? 'Edit Article' : 'Create Article'}</h2>
      <div className="mb-3">
        <label className="form-label">Title</label>
        <input type="text" className="form-control" value={title} onChange={(e) => setTitle(e.target.value)} required />
      </div>
      <div className="mb-3">
        <label className="form-label">Content</label>
        <textarea className="form-control" value={content} onChange={(e) => setContent(e.target.value)} required rows={10} />
      </div>
      <div className="d-flex gap-2">
        <button type="submit" className="btn btn-primary" disabled={loading}>
          {id ? 'Save Changes' : 'Create Article'}
        </button>
        <button type="button" className="btn btn-outline-secondary" onClick={onCancel}>Cancel</button>
      </div>
    </form>
  );
}

interface ArticleListProps {
  articles: Article[];
  onRefresh: () => void;
  onDetail: (id: number) => void;
}

function ArticleList({ articles, onRefresh, onDetail }: ArticleListProps) {

  if (!articles.length) return <div className="empty">No articles yet.</div>;

  return (
    <div className="list-group mt-5 w-75 mx-auto">
      {articles.map((article) => (
        <div key={article.id} className="list-group-item p-4 list-group-item-action d-flex justify-content-between align-items-center">
          <div>
            <h5 className="mb-1">
              <button className="article-link btn btn-link p-0" onClick={() => onDetail(article.id)}>{article.title}</button>
            </h5>
            <p className="article-excerpt mb-1">{article.content.substring(0, 150)}...</p>
            <small className="text-muted">{new Date(article.created_at).toLocaleDateString()}</small>
          </div>
        </div>
      ))}
    </div>
  );
}

type View = 'list' | 'create' | 'detail' | 'edit';

function Articles() {
  const [articles, setArticles] = useState<Article[]>([]);
  const [view, setView] = useState<View>('list');
  const [selectedId, setSelectedId] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const loadArticles = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await fetchArticles();
      setArticles(data);
    } catch (err) {
      setError((err as Error).message);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadArticles();
  }, [loadArticles]);

  const handleDetail = (id: number) => {
    setSelectedId(id);
    setView('detail');
  };

  const handleBack = () => {
    setSelectedId(null);
    setView('list');
  };

  if (view === 'create') {
    return <ArticleForm onCancel={handleBack} />;
  }

  if (view === 'detail' && selectedId) {
    return <ArticleDetail id={selectedId} onBack={handleBack} />;
  }

  if (view === 'edit' && selectedId) {
    return <ArticleForm id={selectedId} onCancel={handleBack} />;
  }

  return (
    <div>
      {loading ? (
        <div className="text-center py-4"><div className="spinner-border" role="status"><span className="visually-hidden">Loading...</span></div></div>
      ) : error ? (
        <div className="alert alert-danger">{error}</div>
      ) : (
        <ArticleList articles={articles} onRefresh={loadArticles} onDetail={handleDetail} />
      )}
    </div>
  );
}

export default Articles;
