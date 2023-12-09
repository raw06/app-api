/* eslint-disable no-undef */
import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig(({ mode }) => {
  // Load app-level env vars to node-level env vars.
  return defineConfig({
    plugins: [react()],
    // base: '/dist/',
    build: {
      manifest: true,
      input: 'resources/js/index.jsx',
      outDir: 'public/dist',
      copyPublicDir: false
    }
    // resolve: {
    //   alias: {
    //     '@': path.resolve(__dirname, 'resources/js'),
    //     '@components': path.resolve(
    //       __dirname,
    //       'resources/js/components'
    //     ),
    //     '@constants': path.resolve(
    //       __dirname,
    //       'resources/js/constants'
    //     ),
    //     '@providers': path.resolve(
    //       __dirname,
    //       'resources/js/providers'
    //     ),
    //     '@hooks': path.resolve(__dirname, 'resources/js/hooks'),
    //     '@utils': path.resolve(__dirname, 'resources/js/utils')
    //   }
    // }
  });
});
