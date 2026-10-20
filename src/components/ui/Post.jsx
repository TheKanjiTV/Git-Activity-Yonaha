
import React, { useState, useEffect } from 'react';
import { getPost, storePost } from '../../api/post';
import './styles/post.css';
import PostCard from './PostCard';

export default function Post() {
  const [posts, setPosts] = useState([]);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [fetchError, setFetchError] = useState('');
  const [loading, setLoading] = useState(true);
  const [processing, setProcessing] = useState(false);

  // Fetch posts on load
  useEffect(() => {
    let active = true;

    const fetchPosts = async () => {
      try {
        const response = await getPost();
        const data = response.data?.data ?? response.data;

        if (!Array.isArray(data)) {
          throw new Error('Unexpected posts response format.');
        }

        if (active) {
          setPosts(data);
          setFetchError('');
        }
      } catch (err) {
        console.error('Failed to load posts:', err);

        if (active) {
          setFetchError('Failed to load posts. Please try again.');
        }
      } finally {
        if (active) {
          setLoading(false);
        }
      }
    };

    fetchPosts();

    return () => {
      active = false;
    };
  }, []);

  // Clear validation error while typing
  useEffect(() => {
    if (message.trim() && error === 'Post cannot be empty') {
      setError('');
    }
  }, [message, error]);

  // Submit a new post
  const submit = async (e) => {
    e.preventDefault();

    if (!message.trim()) {
      setError('Post cannot be empty');
      return;
    }

    setProcessing(true);
    setError('');

    try {
      const response = await storePost({
        message: message.trim(),
      });

      const newPost = response.data?.data ?? response.data;

      if (!newPost || newPost.id == null) {
        throw new Error('The server returned an invalid post.');
      }

      setPosts((prevPosts) => [
        newPost,
        ...prevPosts.filter((post) => post.id !== newPost.id),
      ]);

      setMessage('');
    } catch (err) {
      console.error('Failed to create post:', err);

      const errMsg =
        err.response?.data?.message ||
        err.message ||
        'Failed to share post. Please try again.';

      setError(errMsg);
    } finally {
      setProcessing(false);
    }
  };

  return (
    <div className="post-container">
      <div className="post-card">
        <form onSubmit={submit} className="post-form">
          <textarea
            value={message}
            placeholder="What is happening?!"
            className="post-textarea"
            onChange={(e) => setMessage(e.target.value)}
            disabled={processing}
            aria-label="Write a post"
            aria-invalid={Boolean(error)}
          />

          {error && (
            <div className="post-error" role="alert">
              {error}
            </div>
          )}

          <div className="post-footer">
            <button
              type="submit"
              className="post-submit-btn"
              disabled={processing || !message.trim()}
            >
              {processing ? 'Posting...' : 'Post'}
            </button>
          </div>
        </form>
      </div>

      {fetchError && (
        <div className="post-error" role="alert">
          {fetchError}
        </div>
      )}

      <div className="post-feed-list">
        {loading ? (
          <p>Loading posts...</p>
        ) : posts.length > 0 ? (
          posts.map((post) => (
            <PostCard key={post.id} post={post} />
          ))
        ) : !fetchError ? (
          <div className="post-empty-state">
            No posts shared yet.
          </div>
        ) : null}
      </div>
    </div>
  );
}
