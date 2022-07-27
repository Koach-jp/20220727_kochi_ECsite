<template>
  <div class="product-in-cart-wrap" v-if="product">
    <p v-if="stock" class="alert">
      ※ 在庫が0になりました。カートから削除してください。
    </p>
    <p v-if="quantity == 0 && stock == null" class="alert">
      ※ 注文点数が0です。点数を増やすか、カートから削除してください。
    </p>
    <p v-if="price" class="alert">
      ※ 価格が変更されました。以前の価格：\{{oldPrice.toLocaleString()}} (税込)
    </p>
    <div class="product-in-cart" :class="stock">
      <img :src="'http://127.0.0.1:8000/'+product.img_path" class="product-img">
      <div class="product-info">
        <p>{{product.name}}</p>
        <p :class="price">\{{product.price.toLocaleString()}} (税込)</p>
      </div>
      <div class="quantity-in-cart">
        <p>在庫：{{product.stock}}</p>
        <div>
          <label>点数：
          <input type="number" v-model="quantity">
          </label>
        </div>
        <a @click="deleteProduct">カートから削除</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    cartInfo: Object,
  },
  data() {
    return {
      product: null,
      quantity: this.cartInfo.quantity,
      oldPrice: this.cartInfo.price,
      stock: null,
      price: null,
    }
  },
  methods: {
    // カートに入れた時から価格や在庫が変わっている可能性があるので、新しくapiから取得する。
    async getProduct() {
      const product = await this.$axios.get(`http://127.0.0.1:8000/api/product/${this.cartInfo.id}`);
      this.product = product.data.product;
      if (this.oldPrice !== this.product.price) {
        this.price = 'price-changed';
      }
    },
    deleteProduct() {
      confirm(`商品「${this.product.name}」をカートから削除しますか？`) ? this.$store.commit('deleteProduct', this.product.id) : null;
    }
  },
  updated() {
    const q = +this.quantity;
    if (this.product.stock === 0) {
      this.stock = "no-stock";
      this.quantity = 0;
    } else if (q > +this.product.stock) {
      alert(`商品「${this.product.name}」の在庫が足りません、点数を在庫数まで減らします。`);
      this.quantity = +this.product.stock;
    } else if (q < 0 || !Number.isInteger(q)) {
      alert('不適切な点数です。');
      this.quantity = +this.cartInfo.quantity;
    } 
      const pInfo = {
        id: this.product.id,
        quantity: +this.quantity,
        price: +this.product.price,
      };
      this.$store.commit('changeQAndP', pInfo);
  },
  created() {
    this.getProduct();
  }
}
</script>
-2
<style>
.product-in-cart {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.product-img {
  width: 12vw;
  height: 12vw;
  min-width: 100px;
  min-height: 100px;
  object-fit: cover;
}

.quantity-in-cart a {
  display: inline-block;
  font-size: 11px;
  background: rgb(253, 253, 148);
  color: gray;
  padding: 3px 10px;
  border-radius: 20px;
  font-weight: bold;
}

.quantity-in-cart input {
  width: 50px;
  padding: 3px;
}

.quantity-in-cart div {
  padding: 2vw 0;
}

.product-info {
  width: 50%;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.product-info p {
  padding: 10px 0;
}

.product-info p:first-of-type {
  padding-left: 5%;
}

.product-info p:last-of-type {
  padding-left: 5%;
  padding-right: 10%;
}

.alert {
  font-weight: bold;
  font-size: 14px;
  margin-bottom: 5px;
}

.no-stock {
  background: #ffb7b7;
}

.price-changed {
  font-weight: bold;
  color: rgb(194, 0, 0);
}
</style>