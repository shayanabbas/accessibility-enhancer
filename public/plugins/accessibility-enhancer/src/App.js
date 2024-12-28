import { createRoot } from 'react-dom/client';
import ReportGenerator from './components/ReportGenerator';

// Render Report Generator in admin
const adminDashboardContainer = document.getElementById(
  'accessibility-admin-dashboard'
);
if (adminDashboardContainer) {
  const adminDashboardRoot = createRoot(adminDashboardContainer);
  adminDashboardRoot.render(<ReportGenerator />);
}
