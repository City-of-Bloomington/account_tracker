export default function ({$axios, store, error, redirect }) {
  if (!store.state.auth.isAuthenticated) {
    console.dir('no USER');
  }
}