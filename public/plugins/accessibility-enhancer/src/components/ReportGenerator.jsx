import { useState, useEffect } from 'react';

const ReportGenerator = () => {
  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [filter, setFilter] = useState('all');

  useEffect(() => {
    const fetchReports = async () => {
      setLoading(true);
      try {
        const response = await fetch('/wp-json/accessibility/v1/reports', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            // eslint-disable-next-line no-undef
            'X-WP-Nonce': accessibilityEnhancer.nonce, // Include the nonce passed via wp_localize_script
          },
        });

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

  const filteredReports = reports.filter((report) => {
    if (filter === 'all') return true;
    return report.issues.some((issue) => issue.issue === filter);
  });

  return (
    <div
      className='report-generator'
      role='region'
      aria-labelledby='accessibility-reports-header'
    >
      <h2 id='accessibility-reports-header'>Accessibility Reports</h2>

      {/* Loading and Error Announcements */}
      {loading && (
        <p role='status' aria-live='polite'>
          Loading reports...
        </p>
      )}
      {error && (
        <p role='alert' aria-live='assertive' className='error'>
          {error}
        </p>
      )}

      {!loading && !error && reports.length === 0 && (
        <p role='status' aria-live='polite'>
          No accessibility issues found.
        </p>
      )}

      {!loading && !error && reports.length > 0 && (
        <>
          {/* Filter Section */}
          <fieldset className='filter-container'>
            <legend>Filter Reports</legend>
            <label htmlFor='filter'>Filter by Issue:</label>
            <select
              id='filter'
              onChange={(e) => setFilter(e.target.value)}
              aria-label='Filter accessibility reports by issue type'
            >
              <option value='all'>All</option>
              <option value='Missing alt attribute'>Missing Alt Text</option>
              <option value='Improper heading hierarchy'>Heading Issues</option>
              <option value='Invalid ARIA role'>ARIA Issues</option>
              <option value='Insufficient color contrast'>
                Contrast Issues
              </option>
            </select>
          </fieldset>

          {/* Reports List */}
          <ul aria-live='polite' className='reports-list'>
            {filteredReports.map((report) => (
              <li key={report.post_id} role='listitem'>
                <strong className='post-title'>{report.post_title}</strong>
                <span
                  className={`status ${
                    report.status === 'Fixed' ? 'fixed' : 'issues'
                  }`}
                  aria-label={`Status: ${report.status}`}
                >
                  {report.status}
                </span>
                {report.issues.length > 0 ? (
                  <ul className='issues-list' aria-label='Issues List'>
                    {report.issues
                      .filter(
                        (issue) => filter === 'all' || issue.issue === filter
                      )
                      .map((issue, index) => (
                        <li key={index} role='listitem'>
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
                ) : (
                  <p className='no-issues' role='status'>
                    All issues fixed.
                  </p>
                )}
              </li>
            ))}
          </ul>
        </>
      )}
    </div>
  );
};

export default ReportGenerator;
