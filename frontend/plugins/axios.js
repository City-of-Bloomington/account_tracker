// import https from 'https';

export default function ({ $axios, app, store, redirect, route }) {
  // const agent = new https.Agent({
  //   rejectUnauthorized: false
  // });

  $axios.onRequest(config => {
    // process.env['NODE_TLS_REJECT_UNAUTHORIZED'] = '0';

    // if (process.env.NODE_ENV === 'development') {
    //   config.httpsAgent = agent;
    // }

    // config['xsrfCookieName'] = `csrftoken`
    // config['xsrfHeaderName'] = `X-CSRFToken`

    // config.headers.common['Authorization'] = `Token ${store.state.auth.auth.sessionid}`

    // config.headers.common['X-CSRFToken'] = `${store.state.auth.auth.csrftoken}`

    // config.headers.common['Content-Type']  = `application/json`

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