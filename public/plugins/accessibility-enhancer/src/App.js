import { createRoot } from 'react-dom/client';
import ReportGenerator from './components/ReportGenerator';
import Toolbar from './components/Toolbar';

// Render Toolbar on frontend
const toolbarContainer = document.getElementById('accessibility-toolbar');
if (toolbarContainer) {
  const toolbarRoot = createRoot(toolbarContainer);
  toolbarRoot.render(<Toolbar />);
}

// Render Report Generator in admin
const adminDashboardContainer = document.getElementById(
  'accessibility-admin-dashboard'
);
if (adminDashboardContainer) {
  const adminDashboardRoot = createRoot(adminDashboardContainer);
  adminDashboardRoot.render(<ReportGenerator />);
}
