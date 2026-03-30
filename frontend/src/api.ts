import type { Article, ArticleInput } from './types.ts';

const API_BASE = '/api';

async function handleResponse<T>(res: Response): Promise<T> {
  if (!res.ok) {
    throw new Error(`HTTP error! status: ${res.status}`);
  }
  return res.json() as Promise<T>;
}

export async function fetchArticles(): Promise<Article[]> {
  const res = await fetch(`${API_BASE}/articles`);
  return handleResponse<Article[]>(res);
}

export async function fetchArticle(id: number): Promise<Article> {
  const res = await fetch(`${API_BASE}/articles/${id}`);
  return handleResponse<Article>(res);
}

export async function createArticle(data: ArticleInput): Promise<Article> {
  const res = await fetch(`${API_BASE}/articles`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return handleResponse<Article>(res);
}

export async function updateArticle(id: number, data: ArticleInput): Promise<Article> {
  const res = await fetch(`${API_BASE}/articles/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  const json = await handleResponse<{ success: boolean; data: Article }>(res);
  return json.data;
}

export async function deleteArticle(id: number): Promise<void> {
  const res = await fetch(`${API_BASE}/articles/${id}`, {
    method: 'DELETE',
  });
  if (!res.ok) {
    throw new Error(`HTTP error! status: ${res.status}`);
  }
}
