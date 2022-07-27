<template>
  <div class="cart">
    <h1>ショッピングカート</h1>
    <div class="in-cart-order-wrap">
      <div class="in-cart-wrap">
        <div v-for="product in $store.state.cart" :key="product.id" class="in-cart">
          <Product :cartInfo="product"/>
        </div>
        <p v-if="!$store.state.cart[0]">カートに商品はありません。</p>
      </div>
      <div class="order-wrap">
      <div class="order">
        <div class="sum">合計<br>\{{sum}} 税込</div>
        <button @click="sendOrder" :disabled="!$store.state.cart[0]">注文する</button>
      </div>
      <label class="address">送り先：
        <input type="text" name="住所" v-model="address">
      </label>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',
  data() {
    return {
      address: this.$auth.user.address,
    }
  },
  computed: {
    // カートに入れた時から価格や在庫が変わっている可能性があるので、新しくapiから取得した情報を計算する。api取得はProductコンポーネントで行っている。
    sum() {
      return this.$store.state.cart.reduce((sum,p) => sum + p.quantity * p.price, 0)
      .toLocaleString();
    },
  },
  methods: {
    async sendOrder() {
      if (!this.$store.state.cart[0]) {
        alert('カートは空です');
        return;
      }
      const products = this.$store.state.cart.filter(p => p.quantity === 0);
      if (products[0]) {
        alert('点数が0の商品をカートから削除してください。');
        const boo = confirm('点数が0の商品をカートから一括削除しますか？');
        if (boo) {
          products.forEach(product => {
            this.$store.commit('deleteProduct', product.id);
          });
        }
        return;
      }

      if (!this.address) {
        alert('住所を入力してください');
        return;
      }
      const user = {
        id: this.$auth.user.id,
        address: this.address,
      };
      await this.$axios.post('http://127.0.0.1:8000/api/auth/update', user);
      this.$store.commit('changeAddress', this.address); //リロードしないと再購入時に反映されないのでmutationで対応する。わざわざapiからフェッチするのもトラフィック増やすので。

      const order = {
        user_id: this.$auth.user.id,
        products: this.$store.state.cart,
      };
      const response = await this.$axios.post('http://127.0.0.1:8000/api/order/', order);
      const status = response.status;
      if (status == 201) {
        await this.$router.push({
          name: 'thank',
          query: {order_uuid: response.data.order_uuid},
        });
        this.$store.commit('clearCart');
      } else if (status == 200) {
        alert('在庫が足りなくなった商品があるか、価格が変更になった商品があります。\nページをリロードします。');
        //api側では在庫不足と価格変更で2種類の例外を投げてはいるが、例外を投げた商品以降の商品に在庫不足か価格変更があっても検出できないため、アラート文は1種類で対応する。
        location.reload();
      }
    },
  },
}
</script>

<style>
.cart {
  width: 90%;
  min-height: 90vh;
  margin: 0 auto;
}

.cart h1 {
  font-size: 28px;
  padding: 50px 0 20px;
}

.in-cart-order-wrap {
  display: flex;
}

.in-cart-wrap {
  width: 65%;
}

.in-cart {
  border-top: gainsboro 2px solid;
  padding: 10px 0;
}

.in-cart:last-of-type {
  border-bottom: gainsboro 2px solid;
  margin-bottom: 100px;
}

.order-wrap {
  width: 30%;
  margin-left: 5%;

}

.order {
  background: #f0f0f0;
  text-align: center;
  height: fit-content;
}

.sum {
  font-size: 28px;
  font-weight: bold;
  padding: 40px 0 20px;
  line-height: 2;
}

.order button {
  background: #8aad28;
  border: none;
  color: white;
  border-radius: 20px;
  font-size: 16px;
  width: 90%;
  max-width: 200px;
  margin-bottom: 50px;
}

.address {
  display: block;
  margin: 50px 0;
}
</style>