const API_ROUTES = {
  SHOP_INFO: 'shop-info',
  FILES: {
    ALL: 'files',
    CREATE: 'files/create',
    REMOVE: (id) => `file/${id}`
  }
};

export default API_ROUTES;
