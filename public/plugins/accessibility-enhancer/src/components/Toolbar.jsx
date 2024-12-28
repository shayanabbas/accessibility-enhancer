import { useState, useEffect } from 'react';

const Toolbar = () => {
  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Helper to get the slug from the permalink
  const getSlug = () => {
    const canonicalLink = document.querySelector('link[rel="canonical"]');
    if (canonicalLink) {
      const url = new URL(canonicalLink.href);
      const slug = url.pathname.split('/').filter(Boolean).pop(); // Extract the last part of the path
      return slug;
    }
    return null;
  };

  useEffect(() => {
    const fetchReports = async () => {
      const slug = getSlug();
      if (!slug) return; // Exit if slug is not available
      setLoading(true);
      try {
        const response = await fetch(
          `/wp-json/accessibility/v1/reports?slug=${slug}`
        );
        if (response.ok) {
          const data = await response.json();
          if (data && Array.isArray(data.data)) {
            setReports(data.data);
          } else {
            setReports([]);
          }
        } else {
          throw new Error(`Failed to fetch reports: ${response.status}`);
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchReports();
  }, []);

  const increaseTextSize = () => {
    const root = document.documentElement;
    const currentSize = parseFloat(getComputedStyle(root).fontSize);
    root.style.fontSize = `${currentSize + 2}px`;
  };

  const decreaseTextSize = () => {
    const root = document.documentElement;
    const currentSize = parseFloat(getComputedStyle(root).fontSize);
    root.style.fontSize = `${currentSize - 2}px`;
  };

  const resetTextSize = () => {
    const root = document.documentElement;
    root.style.fontSize = '16px';
  };

  const setContrastMode = (mode) => {
    document.body.className = '';
    if (mode) {
      document.body.classList.add(mode);
    }
  };

  return (
    <div className='toolbar'>
      <h3>Accessibility Toolbar</h3>

      <div className='text-resize'>
        <button onClick={increaseTextSize}>Increase Text Size</button>
        <button onClick={decreaseTextSize}>Decrease Text Size</button>
        <button onClick={resetTextSize}>Reset Text Size</button>
      </div>

      <div className='contrast-controls'>
        <button onClick={() => setContrastMode('high-contrast')}>
          High Contrast
        </button>
        <button onClick={() => setContrastMode('sepia')}>Sepia</button>
        <button onClick={() => setContrastMode('')}>Default Contrast</button>
      </div>

      <div className='reports'>
        <h4>Accessibility Reports</h4>
        {loading && <p>Loading reports...</p>}
        {error && <p className='error'>{error}</p>}
        {!loading && !error && reports.length === 0 && (
          <p>No accessibility issues found for this page.</p>
        )}
        {!loading && !error && reports.length > 0 && (
          <ul>
            {reports.map((report) => (
              <li key={report.post_id}>
                <strong>{report.post_title}</strong>
                <ul>
                  {report.issues.map((issue, index) => (
                    <li key={index}>
                      <strong>{issue.issue}:</strong>{' '}
                      {issue.selector ? (
                        <span>{issue.selector}</span>
                      ) : (
                        <span
                          dangerouslySetInnerHTML={{ __html: issue.html }}
                        />
                      )}
                    </li>
                  ))}
                </ul>
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
};

export default Toolbar;
