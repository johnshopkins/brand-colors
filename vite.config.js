import { resolve } from 'path';
import { defineConfig } from 'vite';

console.log(resolve(__dirname, 'assets'))

export default defineConfig({
  root: resolve(__dirname, 'assets'),
  build: {
    outDir: resolve(__dirname, 'dist'),
    lib: {
      entry: resolve(__dirname, 'assets/js/main.js'),
      name: 'color',
      fileName: 'main',
    },
    target: 'es2018',
  }
});
