import { Redirect } from '@shopify/app-bridge/actions';
import { getSessionToken } from '@shopify/app-bridge-utils';
import axios from 'axios';
import config from '../helpers/config';

function checkHeadersForReauthorization(headers) {
  if (headers['x-shopify-api-request-failure-reauthorize'] === '1') {
    const authUrlHeader =
      headers['x-shopify-api-request-failure-reauthorize-url'] ||
      `/api/auth`;

    const redirect = Redirect.create(window.app);
    redirect.dispatch(
      Redirect.Action.REMOTE,
      authUrlHeader.startsWith('/')
        ? `https://${window.location.host}${authUrlHeader}`
        : authUrlHeader
    );
  }
}

const axiosInstance = axios.create({
  baseURL: `${config.apiUrl}/api/`,
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json'
  },
  cache: 'no-cache'
});

axiosInstance.interceptors.request.use((axiosConfig) => {
  return getSessionToken(window.app) // requires a Shopify App Bridge instance
    .then((token) => {
      axiosConfig.headers.Authorization = `Bearer ${token}`;
      return axiosConfig;
    });
});

axiosInstance.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // cancelTokenSource.cancel('Operation canceled');
      return checkHeadersForReauthorization(error.response.headers);
    }
    if (
      error.response?.status === 302 &&
      error.response?.headers?.location
    ) {
      const redirect = Redirect.create(window.app);
      return redirect.dispatch(
        Redirect.Action.REMOTE,
        error.response.headers.location
      );
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;
