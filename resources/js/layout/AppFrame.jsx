import { Frame } from '@shopify/polaris';
import { Route, Routes } from 'react-router-dom';
import Dashboard from '../pages/Dashboard';
import Files from '../pages/Files';
import ToastProvider from '../hooks/useToast';
import Integration from '../pages/Integration';

export default function AppFrame() {
  return (
    <Frame>
      <ToastProvider>
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/files" element={<Files />} />
          <Route path="/integrations" element={<Integration />} />
        </Routes>
      </ToastProvider>
    </Frame>
  );
}
