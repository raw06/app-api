import React, { useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { Redirect } from '@shopify/app-bridge/actions';
import { useAppBridge, Loading } from '@shopify/app-bridge-react';
import PropTypes from 'prop-types';

export default function ExitIframeProvider({ children }) {
  const app = useAppBridge();
  const { search, pathname } = useLocation();
  useEffect(() => {
    if (!!app && !!search) {
      const params = new URLSearchParams(search);

      const redirectUri = params.get('redirectUri');
      if (!redirectUri) {
        return;
      }
      const url = new URL(decodeURIComponent(redirectUri));

      if (url.hostname === window.location.hostname) {
        const redirect = Redirect.create(app);
        redirect.dispatch(
          Redirect.Action.REMOTE,
          decodeURIComponent(redirectUri)
        );
      }
    }
  }, [app, search]);

  const isExitIframe = pathname === '/ExitIframe';

  return isExitIframe ? <Loading /> : children;
}

ExitIframeProvider.propTypes = {
  // eslint-disable-next-line react/require-default-props
  children: PropTypes.node
};
