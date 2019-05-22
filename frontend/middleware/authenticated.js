export default function({ store, redirect, req }) {


  let isAuthenticated = store.state.auth.isAuthenticated;
  console.log(isAuthenticated);

  // if(!isAuthenticated){
  //   console.dir(isAuthenticated);
  //   return redirect(process.env.api + process.env.login + '/')
  // }
  // console.dir(store.state.auth.authUser);
}


// import Cookies    from 'js-cookie'

// export default function({ store, redirect, req }) {
//   const cookieparser = process.server ? require('cookieparser') : undefined

//   // console.dir(process);
//   console.dir(process.server);
//   console.dir(process.client);
//   console.dir(process.static);

//   if(process.client || process.static) {
//     var clientSessionID = Cookies.getJSON('csrftoken');
//     console.dir(`client SESSID :: ${JSON.stringify(clientSessionID)}`);

//     if(clientSessionID === null || clientSessionID === undefined) {
//       return redirect(process.env.api + process.env.login + '/')
//     }
//   } else {
//     var ssrParsed   = cookieparser.parse(req.headers.cookie),
//     ssrSessionID    = ssrParsed.sessionid;
//     console.dir(`server SESSID :: ${ssrSessionID}`);

//     if(ssrSessionID === null || ssrSessionID === undefined) {
//       return redirect(process.env.api + process.env.login + '/')
//     }
//   }
// }