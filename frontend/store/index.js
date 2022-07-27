export const state = () => ({
  cart: [],
  page: 1,
});

export const mutations = {
  insertToCart(state, product) {
    state.cart.push(product);
  },
  clearCart(state) {
    state.cart = [];
  },
  deleteProduct(state, id) {
    const index = state.cart.findIndex(product => product.id == id);
    state.cart.splice(index, 1);
  },
  changeQAndP(state, pInfo) {
    const index = state.cart.findIndex(product => product.id == pInfo.id);
    if (index !== -1) {
      state.cart[index].quantity = pInfo.quantity;
      state.cart[index].price = pInfo.price;
    }
  },

  changePage(state, page) {
    state.page = page;
  },

  changeAddress(state, address) {
    state.auth.user.address = address;
  },
};