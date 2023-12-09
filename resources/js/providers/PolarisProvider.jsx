/* eslint-disable react/prop-types */
import React, { useCallback } from 'react';
import { AppProvider } from '@shopify/polaris';
import { useNavigate } from 'react-router-dom';
import '@shopify/polaris/build/esm/styles.css';
import translations from '@shopify/polaris/locales/en.json';

function AppBridgeLink({ url, children, external, ...rest }) {
  const navigate = useNavigate();
  const handleClick = useCallback(() => {
    navigate(url);
  }, [navigate, url]);

  const IS_EXTERNAL_LINK_REGEX = /^(?:[a-z][a-z\d+.-]*:|\/\/)/;

  if (external || IS_EXTERNAL_LINK_REGEX.test(url)) {
    return (
      <a
        {...rest}
        href={url}
        target="_blank"
        rel="noopener noreferrer"
      >
        {children}
      </a>
    );
  }

  return (
    // eslint-disable-next-line jsx-a11y/click-events-have-key-events, jsx-a11y/no-static-element-interactions
    <a {...rest} onClick={handleClick}>
      {children}
    </a>
  );
}

const PolarisProvider = ({ children }) => {
  return (
    <AppProvider i18n={translations} linkComponent={AppBridgeLink}>
      {children}
    </AppProvider>
  );
};

export default PolarisProvider;
