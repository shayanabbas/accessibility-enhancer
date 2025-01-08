import { useState, useEffect } from 'react';

const Toolbar = () => {
  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [isCollapsed, setIsCollapsed] = useState(true);

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
          `/wp-json/accessibility/v1/reports?slug=${slug}`,
          {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              // eslint-disable-next-line no-undef
              'X-WP-Nonce': accessibilityEnhancer.nonce, // Include the nonce passed via wp_localize_script
            },
          }
        );
        if (response.ok) {
          const data = await response.json();
          if (data && Array.isArray(data.data)) {
            setReports(data.data);
          } else {
            setReports([]);
          }
        } else if (response.status === 401) {
          setError('Unauthorized: You do not have permission to view reports.');
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

  const toggleCollapse = () => {
    setIsCollapsed(!isCollapsed);
  };

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
    <div
      className={`toolbar ${isCollapsed ? 'collapsed' : ''}`}
      role='toolbar'
      aria-label='Accessibility Toolbar'
    >
      <button
        className='toggle-button'
        onClick={toggleCollapse}
        aria-expanded={!isCollapsed}
        aria-label={
          isCollapsed
            ? 'Expand Accessibility Toolbar'
            : 'Collapse Accessibility Toolbar'
        }
      >
        {isCollapsed ? (
          <span className='dashicons dashicons-universal-access'></span>
        ) : (
          <span className='dashicons dashicons-no-alt'></span>
        )}
      </button>
      {!isCollapsed && (
        <>
          <h3>Accessibility Toolbar</h3>
          <div className='text-resize'>
            <button onClick={increaseTextSize} aria-label='Increase Text Size'>
              Increase Text Size
            </button>
            <button onClick={decreaseTextSize} aria-label='Decrease Text Size'>
              Decrease Text Size
            </button>
            <button onClick={resetTextSize} aria-label='Reset Text Size'>
              Reset Text Size
            </button>
          </div>
          <div className='contrast-controls'>
            <button
              onClick={() => setContrastMode('high-contrast')}
              aria-label='Enable High Contrast Mode'
            >
              High Contrast
            </button>
            <button
              onClick={() => setContrastMode('sepia')}
              aria-label='Enable Sepia Mode'
            >
              Sepia
            </button>
            <button
              onClick={() => setContrastMode('')}
              aria-label='Reset Contrast Mode'
            >
              Default Contrast
            </button>
          </div>
          {!error && (
            <div className='reports'>
              <h4>Accessibility Reports</h4>
              {loading && <p className='loading'>Loading reports...</p>}
              {!loading && reports.length === 0 && (
                <p>No accessibility issues found for this page.</p>
              )}
              {!loading && reports.length > 0 && (
                <ul>
                  {reports.map((report) => (
                    <li key={report.post_id}>
                      <strong>{report.post_title}</strong>
                      <span
                        className={`status ${
                          report.status === 'Fixed' ? 'fixed' : 'issues'
                        }`}
                      >
                        {report.status}
                      </span>
                      <ul>
                        {report.issues.map((issue, index) => (
                          <li key={index}>
                            <strong>{issue.issue}:</strong>{' '}
                            {issue.selector ? (
                              <span>{issue.selector}</span>
                            ) : (
                              <span
                                dangerouslySetInnerHTML={{
                                  __html: issue.html || issue.role,
                                }}
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
          )}
        </>
      )}
    </div>
  );
};

export default Toolbar;
