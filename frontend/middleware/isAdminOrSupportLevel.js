export default function({ store, redirect, req }) {
  let supportLevel    = store.state.groupLevels.support, // 5
      adminLevel      = store.state.groupLevels.admin, // 1
      userLevel       = store.state.auth.authUser.groups,
      isAdminLevel    = userLevel.includes(adminLevel),
      isSupportLevel  = userLevel.includes(supportLevel);

  if (!isAdminLevel) {
    if (!isSupportLevel) {
      console.log(`⛔ 🛸 MUST BE SUPPORT or ADMIN LVL :: (you - ${userLevel}) 🛸 ⛔`);
      return redirect('./')
    }
  }
}