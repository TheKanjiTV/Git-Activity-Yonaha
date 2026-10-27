import { useCallback, useEffect, useState } from 'react';
import './App.css';
import Card from './components/ui/Card';
import Button from './components/ui/Button';
import Form from './components/ui/Form';
import Counter from './components/ui/Counter';
import List from './components/ui/List';
import Post from './components/ui/Post';

const emptyFilters = { search: '', category: '', sort: 'newest', page: 1 };
const money = (value) => Number(value || 0).toFixed(2);

function App() {
  const [tab, setTab] = useState('overview');
  const [dashboard, setDashboard] = useState(null);
  const [filters, setFilters] = useState(emptyFilters);
  const [draft, setDraft] = useState(emptyFilters);
  const [selected, setSelected] = useState(null);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [retry, setRetry] = useState(0);

  const loadDashboard = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const response = await fetch(`/api/v1/dashboard?${new URLSearchParams(filters)}`, {});
      if (!response.ok) throw new Error('Unable to load data. Please try again.');
      setDashboard((await response.json()).data);
    } catch (requestError) {
      setError(requestError.message || 'Unable to load data. Please try again.');
    } finally {
      setLoading(false);
    }
  }, [filters]);

  useEffect(() => { loadDashboard(); }, [loadDashboard, retry]);

  const categories = dashboard?.categories || [];
  const products = dashboard?.products?.data || [];
  const productMeta = dashboard?.products?.meta || {};
  const statistics = dashboard?.statistics || {};
  const categoryName = (product) => {
    const categoryId = product.relationships?.category?.data?.id;
    return categories.find((category) => String(category.id) === String(categoryId))?.name || 'Uncategorized';
  };
  const showProducts = (category = '') => {
    const next = { ...filters, category: String(category), page: 1 };
    setFilters(next); setDraft(next); setSelected(null); setTab('products');
  };
  const submitFilters = (event) => { event.preventDefault(); setSelected(null); setFilters({ ...draft, page: 1 }); };
  const clearFilters = () => { setDraft(emptyFilters); setFilters(emptyFilters); setSelected(null); };
  const changePage = (page) => { setSelected(null); setFilters((current) => ({ ...current, page })); };

  const overview = (
    <section>
      <h2>Overview</h2>
      <div className="stats">
        <div><h3>Total products</h3><p>{statistics.total_products || 0}</p></div>
        <div><h3>Categories</h3><p>{categories.length}</p></div>
        <div><h3>Total value</h3><p>{money(statistics.total_value)}</p></div>
        <div><h3>Average price</h3><p>{money(statistics.average_price)}</p></div>
      </div>
      <h3>Categories</h3>
      <div className="table-container"><table>
        <thead><tr><th>Name</th><th>Products</th><th>Action</th></tr></thead>
        <tbody>{categories.map((category) => <tr key={category.id}>
          <td>{category.name}</td><td>{category.products_count}</td>
          <td><button type="button" onClick={() => showProducts(category.id)}>View products</button></td>
        </tr>)}</tbody>
      </table></div>
    </section>
  );

  const productsView = (
    <section>
      <h2>Products</h2>
      <form className="filters" onSubmit={submitFilters}>
        <label className="filter-field" htmlFor="product-search">Search
          <input id="product-search" value={draft.search} onChange={(event) => setDraft({ ...draft, search: event.target.value })} />
        </label>
        <label className="filter-field" htmlFor="product-category">Category
          <select id="product-category" value={draft.category} onChange={(event) => setDraft({ ...draft, category: event.target.value })}>
            <option value="">All categories</option>
            {categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}
          </select>
        </label>
        <label className="filter-field" htmlFor="product-sort">Sort by
          <select id="product-sort" value={draft.sort} onChange={(event) => setDraft({ ...draft, sort: event.target.value })}>
            <option value="newest">Newest</option><option value="price_asc">Price: low to high</option><option value="price_desc">Price: high to low</option>
          </select>
        </label>
        <button type="submit">Search</button><button type="button" onClick={clearFilters}>Clear</button>
      </form>
      <div className="table-container"><table>
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Action</th></tr></thead>
        <tbody>{products.length ? products.map((product) => <tr key={product.id}>
          <td>{product.attributes.name}</td><td>{categoryName(product)}</td><td className="price">{money(product.attributes.price)}</td>
          <td><button type="button" onClick={() => setSelected(product)}>View</button></td>
        </tr>) : <tr><td colSpan="4">No products found.</td></tr>}</tbody>
      </table></div>
      {selected && <section className="product-details" aria-label="Selected product details">
        <button type="button" onClick={() => setSelected(null)}>Close details</button>
        <h3>{selected.attributes.name}</h3>
        <p>{selected.attributes.description || 'No description available.'}</p>
        <p>Category: {categoryName(selected)}</p><p>Price: {money(selected.attributes.price)}</p>
      </section>}
      <div className="pagination">
        <span>Page {productMeta.current_page || 1} of {productMeta.last_page || 1}</span>
        <div>
          <button type="button" disabled={(productMeta.current_page || 1) <= 1} onClick={() => changePage(productMeta.current_page - 1)}>Previous</button>
          <button type="button" disabled={(productMeta.current_page || 1) >= (productMeta.last_page || 1)} onClick={() => changePage(productMeta.current_page + 1)}>Next</button>
        </div>
      </div>
    </section>
  );

  const categoriesView = (
    <section><h2>Categories</h2>
      <div className="table-container" role="region" aria-label="Category list"><table>
        <thead><tr><th>Name</th><th>Description</th><th>Products</th><th>Action</th></tr></thead>
        <tbody>{categories.map((category) => <tr key={category.id}>
          <td>{category.name}</td><td>{category.description || '-'}</td><td>{category.products_count}</td>
          <td><button type="button" onClick={() => showProducts(category.id)}>View products</button></td>
        </tr>)}</tbody>
      </table></div>
    </section>
  );

  const components = (
    <section className="component-demo">
      <h2>Components</h2>
      <Card name="Kenji" description="This is me." />
      <Card name="Yonaha" description="This is me again." />
      <Button text="Click Me" onClick={() => alert('Hello Kenji!')} />
      <Form /><Counter /><List /><Post />
    </section>
  );

  return (
    <main className="app">
      <header className="header">
        <h1>Product Dashboard</h1>
        <nav aria-label="Dashboard sections">
          {['overview', 'products', 'categories', 'components'].map((name) => <button key={name} type="button" className={tab === name ? 'active' : ''} onClick={() => setTab(name)}>{name[0].toUpperCase() + name.slice(1)}</button>)}
        </nav>
      </header>
      {loading ? <p>Loading...</p> : error ? <div className="error" role="alert"><p>{error}</p><button type="button" onClick={() => setRetry((value) => value + 1)}>Try again</button></div> : <>
        {tab === 'overview' && overview}{tab === 'products' && productsView}{tab === 'categories' && categoriesView}{tab === 'components' && components}
      </>}
    </main>
  );
}

export default App;
