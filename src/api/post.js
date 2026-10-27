import api from './axios';

export const getPost = () => {
  return api.get('/posts');
};

export const storePost = (data) => {
  return api.post('/posts', data);
};