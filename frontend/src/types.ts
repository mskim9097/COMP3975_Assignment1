export interface Article {
  id: number;
  title: string;
  content: string;
  created_at: string;
  updated_at: string;
}

export interface ArticleInput {
  title: string;
  content: string;
}
