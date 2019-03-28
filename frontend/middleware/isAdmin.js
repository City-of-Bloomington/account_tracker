export default function ({ store, redirect, isServer, res, req, query }) {
  let adminLevel  = store.state.groupLevels.admin,
  userLevel       = store.state.authUser.groups,
  isAdminLevel    = userLevel.includes(adminLevel);

  console.log(store.state.authUser);

  if (!isAdminLevel) {
    console.log(`⛔ 🛸 page level = Admin (${userLevel}) 🛸 ⛔`);
    return redirect('/error')
  }
}