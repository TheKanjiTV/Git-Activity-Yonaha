
import React, { useState, useEffect } from 'react';
import './styles/post.css';

const POSTS_STORAGE_KEY = 'laravel-f1-posts';

function PostCard({ post }) {
  return (
    <article className="post-feed-card">
      <p className="post-feed-message">{post.message}</p>
      <time className="post-feed-time" dateTime={post.createdAt}>
        {new Date(post.createdAt).toLocaleString()}
      </time>
    </article>
  );
}

export default function Post() {
  const [posts, setPosts] = useState([]);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [fetchError, setFetchError] = useState('');
  const [processing, setProcessing] = useState(false);

  // Keep this standalone UI working without a posts API in the project.
  useEffect(() => {
    try {
      const savedPosts = window.localStorage.getItem(POSTS_STORAGE_KEY);
      if (savedPosts) {
        const parsedPosts = JSON.parse(savedPosts);
        if (Array.isArray(parsedPosts)) setPosts(parsedPosts);
      }
    } catch (storageError) {
      setFetchError('Saved posts could not be read in this browser.');
    }
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
      const newPost = {
        id: `${Date.now()}-${Math.random().toString(36).slice(2)}`,
        message: message.trim(),
        createdAt: new Date().toISOString(),
      };
      const updatedPosts = [newPost, ...posts];
      window.localStorage.setItem(POSTS_STORAGE_KEY, JSON.stringify(updatedPosts));
      setPosts(updatedPosts);
      setMessage('');
    } catch (storageError) {
      setError('Could not save this post in your browser.');
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
        {posts.length > 0 ? (
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
