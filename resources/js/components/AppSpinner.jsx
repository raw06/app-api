import { Spinner } from '@shopify/polaris';

const AppSpinner = () => {
  return (
    <div
      style={{
        width: '100%',
        height: '100%',
        position: 'fixed',
        alignItems: 'center',
        justifyContent: 'center',
        display: 'flex'
      }}
    >
      <Spinner accessibilityLabel="Lai loading" size="large" />
    </div>
  );
};

export default AppSpinner;
