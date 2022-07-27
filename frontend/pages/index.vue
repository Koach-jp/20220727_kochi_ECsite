<template>
  <div class="main">
    <div class="eyecatch">
      <div class="title">
        <h1>New Arrivals</h1>
        <p>for BOYS &amp; GIRLS</p>
      </div>
    </div>
    <div class="products">
      <h1>Products</h1>
      <div class="flex-wrap">
        <a class="card" v-for="product in products" :key="product.id" @click="pushToProduct(product.id)">
          <img :src="'http://127.0.0.1:8000/' + product.img_path" alt="No-img">
          <h3>{{product.name}}</h3>
          <p>\{{product.price.toLocaleString()}} (税込)</p>
        </a>
      </div>
    </div>
    <nav class="pagination">
      <ul>
        <li v-for="(link, index) in pagination" :key="index" @click="getProducts(link.label)">
          <a v-if="isFinite(link.label) && isAround(link.label)" :class="{active: link.active}">
            {{link.label}}
          </a>
          <span v-if="isFinite(link.label) && !isAround(link.label)" :class="{active: link.active}">.</span>
        </li>
      </ul>
      <ul>
        <button :disabled="$store.state.page<=1" @click="getProducts(+$store.state.page - 1)">前へ</button>
        <button :disabled="$store.state.page >= last_page"
        @click="getProducts(+$store.state.page + 1)">次へ</button>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: null,
      pagination: null,
      last_page: null,
    }
  },
  methods: {
    async getProducts(page) {
      if (page == null) {
        page = this.$store.state.page;
      }
      this.$store.commit('changePage', page);
      const products = await this.$axios.get(`http://127.0.0.1:8000/api/product?page=${page}`);
      this.last_page = products.data.products.last_page;
      this.pagination = products.data.products.links;
      this.products = products.data.products.data;
    },
    pushToProduct(id) {
      this.$router.push({
        path: '/products/'+id,
        params: {id: id},
      });
    },
    isAround(page) {
      const c = this.$store.state.page //c = current_page
      const p = page;
      const l = this.last_page
      return p == c || p == +c + 1 || p == +c - 1
      || p == 1 || ((c == 1 || c == 2) && (p == 3 || p == 4))
      || p == l
      || ((c == l || c == +l - 1) && (p == +l - 2 || p == +l -3));
    },
  },
  created() {
    this.getProducts();
  }
}
</script>



<style>
.main {
  width: 90%;
  margin: 0 auto;
  position: relative;
}

.eyecatch {
  background: url(~assets/img/sandal.jpg) no-repeat;
  background-size: cover;
  height: 50vw;
  max-height: 800px;
  background-position: center;
  display: flex;
  justify-content: right;
  align-items: center;
  margin-top: 20px;
}

.title {
  padding: 10vw;
}

.title h1 {
  font-size: 4vw;
}
.title p {
  font-size: 1.5vw;
  font-weight: bold;
  margin-top: 10px;
}

.products h1 {
  text-align: center;
  font-size: 26px;
  padding: 30px 0;
}

.flex-wrap {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
}

.card {
  width: 23%;
  margin-bottom: 15px;
}

.card h3 {
  font-weight: normal;
  padding-bottom: 3px;
}

.card img {
  width: 100%;
  height: 15vw;
  object-fit: cover;
}

.pagination {
  margin: 50px 0;
}

.pagination ul {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.pagination li a {
  display: block;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  padding: 3px 10px;
  width: 60px;
  border: #8aad28 2px solid;
  border-radius: 30px;
  text-align: center;
  margin: 0 5px;
}

.pagination ul button {
  background: none;
  font-size: 12px;
  font-weight: bold;
  padding: 1px 10px;
  width: 60px;
  border: #8aad28 2px solid;
  border-radius: 30px;
  text-align: center;
  margin: 10px 5px 0;
}

.active {
  color: white;
  background: #8aad28;
}
</style>