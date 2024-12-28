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
        const response = await fetch('/wp-json/accessibility/v1/reports');
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
    <div className='report-generator'>
      <h2>Accessibility Reports</h2>
      {loading && <p>Loading reports...</p>}
      {error && <p className='error'>{error}</p>}
      {!loading && !error && reports.length === 0 && (
        <p>No accessibility issues found.</p>
      )}
      {!loading && !error && reports.length > 0 && (
        <>
          <select onChange={(e) => setFilter(e.target.value)}>
            <option value='all'>All</option>
            <option value='Missing alt attribute'>Missing Alt Text</option>
            <option value='Improper heading hierarchy'>Heading Issues</option>
            <option value='Invalid ARIA role'>ARIA Issues</option>
            <option value='Insufficient color contrast'>Contrast Issues</option>
          </select>
          <ul>
            {filteredReports.map((report) => (
              <li key={report.post_id}>
                <strong>{report.post_title}</strong>
                <ul>
                  {report.issues
                    .filter(
                      (issue) => filter === 'all' || issue.issue === filter
                    )
                    .map((issue, index) => (
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
        </>
      )}
    </div>
  );
};

export default ReportGenerator;
