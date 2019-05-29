export default function({ store, redirect, req }) {
  let supportLevel  = store.state.groupLevels.support,
  userLevel         = store.state.auth.authUser.groups,
  isSupportLevel    = userLevel.includes(supportLevel);

  if (!isSupportLevel) {
    console.log(`⛔ 🛸 MUST BE SUPPORT LVL :: (you - ${userLevel}) 🛸 ⛔`);
    return redirect('./')
  }
}