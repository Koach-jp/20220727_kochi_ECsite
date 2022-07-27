<template>
  <header>
    <div class="header flex">
      <h1 class="trace"><NuxtLink to="/">Trace</NuxtLink></h1>
      <nav class="header__navi">
        <ul>
          <li class="navi__list" v-if="$auth.loggedIn">
            Name : {{$auth.user.name}}
          </li>
          <li class="navi__list">
            <NuxtLink to="/cart">
              <img src="~assets/img/cart.png" alt="Cart" class="cart-logo">
            </NuxtLink>
          </li>
          <li class="navi__list">
            <NuxtLink to="/history">
              <img src="~assets/img/user.png" alt="History" class="user-logo">
            </NuxtLink>
          </li>
          <li class="navi__list" v-if="!$auth.loggedIn">
            <NuxtLink to="/login-register">ログイン・新規会員登録</NuxtLink>
          </li>
          <li class="navi__list" v-else>
            <img src="~assets/img/logout.png" @click="logout" class="logout-logo">
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  methods:{
    async logout() {
      const boo = confirm('ログアウトしますか？');
      if (boo) {
        try {
          this.$store.commit('clearCart');
          this.$store.commit('changePage', 1);
        await this.$auth.logout();
        this.$router.push("/login-register");
        } catch (error) {
          alert(error);
        }
      }
    },
  },
}
</script>

<style>
header{
  margin-bottom: 10px;
  width: 90%;
  margin: 0 auto;
}

.header {
  border-bottom: gainsboro 1px solid;
  height: 5vh;
}

.trace {
  font-size: 30px;
}

.flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header__navi ul {
  display: flex;
  align-items: center;
}

.navi__list {
  padding: 0 15px;
}

.user-logo, .cart-logo, .logout-logo {
  width: 25px;
  cursor: pointer;
}

</style>