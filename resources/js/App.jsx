import { BrowserRouter } from 'react-router-dom';
import { QueryParamProvider } from 'use-query-params';
import { ReactRouter6Adapter } from 'use-query-params/adapters/react-router-6';
import { AppBridgeProvider } from './providers/AppBridgeProvider';
import PolarisProvider from './providers/PolarisProvider';
import ReactQueryProvider from './providers/ReactQueryProvider';
import ExitIframeProvider from './providers/ExitIframeProvider';

import '@shopify/polaris/build/esm/styles.css';
import AppFrame from './layout/AppFrame';
import ShopProvider from './providers/ShopProvider';

export default function App() {
  return (
    <PolarisProvider>
      <ReactQueryProvider>
        <BrowserRouter>
          <QueryParamProvider adapter={ReactRouter6Adapter}>
            <AppBridgeProvider>
              <ExitIframeProvider>
                <ShopProvider>
                  <AppFrame />
                </ShopProvider>
              </ExitIframeProvider>
            </AppBridgeProvider>
          </QueryParamProvider>
        </BrowserRouter>
      </ReactQueryProvider>
    </PolarisProvider>
  );
}
