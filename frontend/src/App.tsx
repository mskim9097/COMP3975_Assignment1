import Articles from './Articles';
import './App.css';

function App() {
  return (
    <div className="app">
      <nav className="navbar">
        <span className="nav-brand">ArticleApp</span>
      </nav>
      <main className="container">
        <Articles />
      </main>
    </div>
  );
}

export default App;
