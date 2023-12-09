import { useMemo } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import axiosInstance from '../utils/api';

export const useAppQuery = ({
  url,
  params = {},
  axiosConfig = {},
  reactQueryOptions = {}
}) => {
  const fetch = useMemo(() => {
    return async () => {
      const response = await axiosInstance.get(url, {
        ...axiosConfig,
        params
      });
      return response.data;
    };
  }, [axiosConfig, params, url]);

  return useQuery([url, params], fetch, {
    ...reactQueryOptions,
    refetchOnWindowFocus: false
  });
};

export const useAppMutation = ({
  url,
  method = 'POST',
  axiosConfig = {},
  reactQueryOptions = {}
}) => {
  return useMutation({
    mutationFn: async (variables) => {
      const response = await axiosInstance.request({
        ...axiosConfig,
        data: variables,
        url,
        method
      });
      return response.data;
    },
    ...reactQueryOptions
  });
};
