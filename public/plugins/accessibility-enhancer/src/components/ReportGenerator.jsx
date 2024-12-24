import React, { useState, useEffect } from 'react';

const ReportGenerator = () => {
    const [reports, setReports] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch(`${ACCESSIBILITY_API_URL}/reports`)
            .then((res) => res.json())
            .then((data) => {
                setReports(data.data);
                setLoading(false);
            })
            .catch((error) => console.error('Error fetching reports:', error));
    }, []);

    if (loading) return <p>Loading reports...</p>;

    return (
        <div className="report-generator">
            <h2>Accessibility Reports</h2>
            <ul>
                {reports.map((report, index) => (
                    <li key={index}>{report}</li>
                ))}
            </ul>
        </div>
    );
};

export default ReportGenerator;
