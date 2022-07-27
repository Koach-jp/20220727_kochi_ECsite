<template>
<div class="product-wrap">
  <div class="product" v-if="product">
    <div class="detail">
      <img :src="'http://127.0.0.1:8000/'+product.img_path" alt="No-img">
      <div class="text">
        <h2 class="name">{{product.name}}</h2>
        <h1 class="price">\{{product.price.toLocaleString()}} (税込)</h1>
        <p class="stock" :class="stock">在庫：{{product.stock}}</p>
        <label for="quantity" class="quantity">注文点数：</label>
        <input type="number" id="quantity" v-model="quantity" class="quantity">
        <button @click="addToCart" class="add">
          <img src="~assets/img/whitecart.png">
          <!-- カート画像とテキストの両方をボタンの中央に配置したいので::beforeを使わずにimgで入れる -->
          カートに入れる
        </button>
      </div>
    </div>
    <div class="description">
      <h3>{{product.title}}</h3>
      <p>{{product.description}}</p>
    </div>
  </div>
</div>
</template>

<script>
export default {
  middleware: 'auth',
  data() {
    return {
      product: null,
      quantity: 1,
      stock: null,
    }
  },
  methods: {
    async getProduct() {
      const product = await this.$axios.get(`http://127.0.0.1:8000/api/product/${this.$route.params.id}`);
      this.product = product.data.product;
    },
    addToCart() {
      const boo = this.$store.state.cart.some(p => p.id == this.product.id);
      if (boo) {
        alert(`商品「${this.product.name}」はすでにカート内に入っています。`);
        return;
      }
      if (this.product.stock == 0) {
        alert(`商品「${this.product.name}」の在庫は0です。購入できません。`);
        return;
      }
      const i = +this.quantity; //+を付けることでquantityが文字列として扱われることを防ぐ。
      if (i > this.product.stock) {
        alert('在庫が足りません。');
      } else if (i == 0) {
        alert('購入点数を指定してください。');
        this.quantity = 1;
      } else if (i < 0 || !Number.isInteger(i)) {
        alert('購入点数が不適切です。');
      } else {
        const product = {
          id: this.product.id,
          quantity: i,
          price: this.product.price,
        };
        this.$store.commit("insertToCart", product);
        confirm('カートに商品を追加しました。ショッピングを続けますか？')?
        this.$router.push('/') : this.$router.push('/cart');
      }
    }
  },
  updated() {
    const q = +this.quantity;
      if (this.product.stock == 0) {
        this.stock = 'red';
        this.quantity = 0;
      } else if (q > +this.product.stock) {
        alert(`商品「${this.product.name}」の在庫はこれ以上ありません。`);
        this.quantity = +this.product.stock;
      } else if (q < 0) {
        alert('点数が不適切です。');
        this.quantity = 1;
      } else if (!Number.isInteger(q)) {
        alert('点数が不適切です。');
        this.quantity = Math.floor(q);
      }
  },
  created() {
    this.getProduct();
  },
}
</script>

<style>
.product-wrap {
  width: 60%;
  height: 90vh;
  margin: 0 auto;
  display: flex;
  align-items: center;
}

.product {
  transform: translateY(-35px);
}

.detail {
  display: flex;
  margin-bottom: 50px;
}

.detail img {
  max-width: 300px;
  width: 50%;
  height: auto;
}

.text {
  padding-left: 30px;
  margin: auto 0;
}

.name{
  font-size: 20px;
  margin-bottom: 20px;
}

.price {
  font-size: clamp(20px,4vw,36px);
  margin-bottom: 20px;
}

.stock {
  margin-bottom: 22px;
}

.quantity {
  margin-bottom: 15px;
  vertical-align: baseline;
}

input.quantity {
  border: none;
  background: #EFEFEF;
  width: 100px;
  padding: 2px 5px;
  font-size: 14px;
}

.add {
  display: block;
  color: white;
  background: #8aad28;
  border: none;
  border-radius: 20px;
  width: 100%;
  min-width: 150px;
  font-size: 16px;
  margin-top: 10px;
}

.add img {
  width: 21px;
  height: auto;
  vertical-align: middle;
  transform: translateY(-1px);
}

.description h3 {
  font-size: 18px;
  padding-bottom: 20px;
}

.red {
  color: red;
}
</style>