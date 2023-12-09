import { createContext, useContext } from 'react';
import { useShopInfoQuery } from '../api';
import AppSpinner from '../components/AppSpinner';

const initValue = {
  id: 0,
  info: {
    name: ''
  }
};
const ShopContext = createContext(initValue);

// eslint-disable-next-line react/prop-types
function ShopProvider({ children }) {
  const { data, isFetching } = useShopInfoQuery();

  if (isFetching) {
    return <AppSpinner />;
  }

  return (
    <ShopContext.Provider value={{ ...data }}>
      {children}
    </ShopContext.Provider>
  );
}

const useShop = () => {
  const context = useContext(ShopContext);

  if (context === undefined) {
    throw new Error('useShop must be used within a ShopProvider');
  }

  return context;
};

export default ShopProvider;
export { useShop };
