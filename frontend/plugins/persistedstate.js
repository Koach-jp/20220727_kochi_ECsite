import createPersistedState from 'vuex-persistedstate'

export default ({ store }) => {
  createPersistedState({
    key: 'ECSiteCartLocalStorage',
    paths: ['cart', 'page'],
    storage: window.localStorage,
  })(store)
}