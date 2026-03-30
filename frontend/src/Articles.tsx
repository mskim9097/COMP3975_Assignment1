import { useState, useEffect, useCallback } from 'react';
import type { Article } from './types';
import { fetchArticles, fetchArticle } from './api';

interface ArticleDetailProps {
  id: number;
  onBack: () => void;
}

interface ArticleListProps {
  articles: Article[];
  onDetail: (id: number) => void;
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

  if (loading) {
    return (
      <div className="text-center py-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Loading...</span>
        </div>
      </div>
    );
  }

  if (!article) {
    return <div className="alert alert-danger mt-4">Article not found.</div>;
  }

  return (
    <section className="article-detail-page container py-5">
      <button className="back-link mb-4" onClick={onBack}>
        ← All Articles
      </button>

      <article className="article-detail-card">
        <span className="article-badge">Latest Article</span>
        <h1 className="article-detail-title">{article.title}</h1>
        <p className="article-date">
          {new Date(article.created_at).toLocaleDateString()}
        </p>
        <div className="article-divider" />
        <div className="article-content">{article.content}</div>
      </article>
    </section>
  );
}

function ArticleList({ articles, onDetail }: ArticleListProps) {
  if (!articles.length) {
    return <div className="empty-state">No articles yet.</div>;
  }

  return (
    <div className="article-grid">
      {articles.map((article, index) => (
        <article
          key={article.id}
          className={`article-card ${index === 0 ? 'article-card-featured' : ''}`}
        >
          <div className="article-card-inner">
            <div className="article-card-top">
              <span className="article-tag">{index === 0 ? 'Latest ' : 'Article '}</span>
              <span className="article-card-date">
                {new Date(article.created_at).toLocaleDateString()}
              </span>
            </div>

            <h2 className="article-card-title">
              <button
                className="article-link"
                onClick={() => onDetail(article.id)}
              >
                {article.title}
              </button>
            </h2>

            <p className="article-excerpt">
              {article.content.substring(0, 160)}...
            </p>

            <div className="article-card-footer">
              <button
                className="read-more-btn"
                onClick={() => onDetail(article.id)}
              >
                View Article
              </button>
            </div>
          </div>
        </article>
      ))}
    </div>
  );
}

type View = 'list' | 'detail';

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

  if (view === 'detail' && selectedId) {
    return <ArticleDetail id={selectedId} onBack={handleBack} />;
  }

  return (
    <div className="articles-page">
      <section className="hero-section">
        <div className="container hero-content">
          <h1 className="hero-title">Latest Articles</h1>
          <p className="hero-subtitle">
            Browse the latest updates and articles from Mini-CMS.
          </p>
        </div>
      </section>

      <main className="container py-5">
        {loading ? (
          <div className="text-center py-5">
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Loading...</span>
            </div>
          </div>
        ) : error ? (
          <div className="alert alert-danger">{error}</div>
        ) : (
          <ArticleList articles={articles} onDetail={handleDetail} />
        )}
      </main>
    </div>
  );
}

export default Articles;