export default function ({ $axios, redirect }) {
  $axios.defaults.withCredentials = true;

  $axios.onError(error => {
    console.dir(`AXIOS failure -- ${error}`);
  })
}