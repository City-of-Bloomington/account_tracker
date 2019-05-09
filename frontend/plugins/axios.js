export default function ({ $axios, app, store, redirect, route }) {
  $axios.onRequest(config => {
    // config.headers.common['Authorization'] = `JWT ${store.state.auth.auth}`
    config.headers.common['Content-Type']  = `application/json`

    if(process.env.NODE_ENV === 'development')
      console.log(`${config.method.toUpperCase()} :: ${config.url}`)
  })

  $axios.onError(error => {
    const code = parseInt(error.response && error.response.status)
    if (code === 400) {
      // redirect('/400')
    }

    if (code === 401) {
      // redirect('/login')
    }

    if (code === 404) {
      redirect('/error')
    }
  })
}