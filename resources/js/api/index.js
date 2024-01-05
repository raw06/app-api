import API_ROUTES from '../constants/api';
import { useAppMutation, useAppQuery } from '../hooks/useAppQuery';

export const useShopInfoQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: API_ROUTES.SHOP_INFO,
    reactQueryOptions
  });
};

export const useFilesQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: API_ROUTES.FILES.ALL,
    reactQueryOptions
  });
};

export const useFilesMutation = (reactQueryOptions = {}) => {
  return useAppMutation({
    url: API_ROUTES.FILES.CREATE,
    reactQueryOptions
  });
};

export const useDeleteFilesMutation = (
  id,
  reactQueryOptions = {}
) => {
  return useAppMutation({
    method: 'DELETE',
    url: API_ROUTES.FILES.REMOVE(id),
    reactQueryOptions
  });
};

export const usePartnerQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: API_ROUTES.PARTNER,
    reactQueryOptions
  });
};
