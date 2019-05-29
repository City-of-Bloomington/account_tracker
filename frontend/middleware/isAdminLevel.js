export default function({ store, redirect, req }) {
  let adminLevel  = store.state.groupLevels.admin,
  userLevel         = store.state.auth.authUser.groups,
  isAdminLevel      = userLevel.includes(adminLevel);

  if (!isAdminLevel) {
    console.log(`⛔ 🛸 MUST BE ADMIN LVL :: (you - ${userLevel}) 🛸 ⛔`);
    return redirect('./')
  }
}