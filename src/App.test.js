import { fireEvent, render, screen, waitFor, within } from '@testing-library/react';
import App from './App';

const fixture = {
  data: {
    statistics: { total_products: 12, total_value: 300, average_price: 25 },
    categories: [{ id: 1, name: 'Books', description: 'Reading materials', products_count: 12 }],
    products: {
      data: [{
        id: 1,
        attributes: { name: 'Laravel Guide', description: 'A book about Laravel.', price: 25 },
        relationships: { category: { data: { id: 1 } } },
      }],
      meta: { total: 12, current_page: 1, last_page: 2 },
    },
  },
};

beforeEach(() => {
  global.fetch = jest.fn(async url => {
    const page = Number(new URL(url, 'http://localhost').searchParams.get('page'));
    return {
      ok: true,
      json: async () => ({
        data: {
          ...fixture.data,
          products: { ...fixture.data.products, meta: { ...fixture.data.products.meta, current_page: page } },
        },
      }),
    };
  });
});

afterEach(() => {
  jest.restoreAllMocks();
});

test('shows the overview and navigates to products and categories', async () => {
  render(<App />);
  expect(await screen.findByRole('heading', { name: 'Overview' })).toBeInTheDocument();
  expect(screen.getByText('300.00')).toBeInTheDocument();

  fireEvent.click(screen.getByRole('button', { name: 'Products', exact: true }));
  expect(screen.getByText('Laravel Guide')).toBeInTheDocument();
  fireEvent.click(screen.getByRole('button', { name: 'View', exact: true }));
  expect(screen.getByRole('region', { name: 'Selected product details' })).toHaveTextContent('A book about Laravel.');
  fireEvent.click(screen.getByRole('button', { name: 'Close details' }));
  expect(screen.queryByRole('region', { name: 'Selected product details' })).not.toBeInTheDocument();

  fireEvent.click(screen.getByRole('button', { name: 'Categories', exact: true }));
  expect(screen.getByRole('region', { name: 'Category list' })).toHaveTextContent('Reading materials');
});

test('search and pagination send the chosen filters to Laravel', async () => {
  render(<App />);
  await screen.findByRole('heading', { name: 'Overview' });
  fireEvent.click(screen.getByRole('button', { name: 'Products', exact: true }));
  fireEvent.change(screen.getByLabelText('Search'), { target: { value: 'Laravel' } });
  fireEvent.change(screen.getByLabelText('Category'), { target: { value: '1' } });
  fireEvent.change(screen.getByLabelText('Sort by'), { target: { value: 'price_asc' } });
  fireEvent.click(screen.getByRole('button', { name: 'Search', exact: true }));

  await waitFor(() => expect(fetch).toHaveBeenLastCalledWith(
    '/api/v1/dashboard?search=Laravel&category=1&sort=price_asc&page=1', expect.any(Object)
  ));
  await screen.findByRole('button', { name: 'Next' });
  fireEvent.click(screen.getByRole('button', { name: 'Next' }));
  await waitFor(() => expect(fetch).toHaveBeenLastCalledWith(
    '/api/v1/dashboard?search=Laravel&category=1&sort=price_asc&page=2', expect.any(Object)
  ));
});

test('view products in a category opens the filtered product list', async () => {
  render(<App />);
  await screen.findByRole('heading', { name: 'Overview' });
  fireEvent.click(screen.getByRole('button', { name: 'Categories', exact: true }));
  fireEvent.click(within(screen.getByRole('region', { name: 'Category list' })).getByRole('button', { name: 'View products' }));

  await waitFor(() => expect(fetch).toHaveBeenLastCalledWith(
    '/api/v1/dashboard?search=&category=1&sort=newest&page=1', expect.any(Object)
  ));
  expect(await screen.findByRole('heading', { name: 'Products' })).toBeInTheDocument();
});

test('failed API requests show a retry button and recover', async () => {
  fetch.mockRejectedValueOnce(new Error('Unable to load data. Please try again.'));
  render(<App />);
  expect(await screen.findByRole('alert')).toHaveTextContent('Unable to load data');
  fireEvent.click(screen.getByRole('button', { name: 'Try again' }));
  expect(await screen.findByRole('heading', { name: 'Overview' })).toBeInTheDocument();
});
