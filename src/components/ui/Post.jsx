import React, { useState, useEffect } from 'react';
import { getPost, storePost } from '../../api/post';
import './styles/post.css';
import PostCard from './PostCard';

export default function Post() {
  const [posts, setPosts] = useState([]);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [processing, setProcessing] = useState(false);
  const [loading, setLoading] = useState(true);

  // 1. Fetch posts on load
  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const response = await getPost();
        const responsePosts = response.data?.data ?? response.data;
        setPosts(Array.isArray(responsePosts) ? responsePosts : []);
      } catch (err) {
        console.error("Failed to load posts", err);
        setError('Could not load posts. Check the Laravel server and try again.');
      } finally {
        setLoading(false);
      }
    };

    fetchPosts();
  }, []);

  // 2. Clear error while typing
  useEffect(() => {
    if (message.trim() && error) {
      setError('');
    }
  }, [message, error]);

  // 3. Submit function
  const submit = async (e) => {
    e.preventDefault();

    if (!message.trim()) {
      setError('Post cannot be empty');
      return;
    }

    setProcessing(true);
    setError('');

    try {
      const response = await storePost({ message: message.trim() });
      const newPost = response.data?.data ?? response.data;

      if (!newPost || typeof newPost.id === 'undefined') {
        throw new Error('The server returned an invalid post.');
      }

      setPosts((prevPosts) => [newPost, ...prevPosts]);
      setMessage('');
    } catch (err) {
      const validationMessage = err.response?.data?.errors?.message?.[0];
      const errMsg = validationMessage
        || (err.response?.status >= 500
          ? 'The server could not save your post. Check the Laravel database migration and try again.'
          : err.response?.data?.message)
        || (err.code === 'ECONNABORTED'
          ? 'The server took too long to respond. Please try again.'
          : 'Failed to share post. Please try again.');
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
            aria-label="Post message"
            value={message}
            placeholder="What is happening?!"
            className="post-textarea"
            onChange={(e) => setMessage(e.target.value)}
            disabled={processing}
          />

          {error && (
            <div className="post-error">
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

      {/* Post feed list na may ternary operator para sa empty state */}
      {loading && <p role="status" className="post-status">Loading posts...</p>}
      {!loading && <div className="post-feed-list" aria-live="polite">
        {posts.length > 0 ? (
          posts.map((post) => (
            <PostCard key={post.id ?? `${post.created_at}-${post.message}`} post={post} />
          ))
        ) : (
          <div className="post-empty-state">No posts shared yet.</div>
        )}
      </div>}
    </div>
  );
}
