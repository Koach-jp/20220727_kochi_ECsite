<template>
  <div class="history" >
    <h1>購入履歴</h1>
    <div class="orders-wrap">
      <div class="orders" v-for="order in orders" :key="order.id" >
        <h2>注文番号：{{order.uuid}}</h2>
        <div class="order-details" v-for="orderDetail in order.order_details" :key="orderDetail.id">
          <img :src="`http://127.0.0.1:8000/${orderDetail.product.img_path}`" class="product-img">
          <div class="order-details__text">
            <p>注文番号：{{orderDetail.order_uuid}}</p>
            <div class="order-details__product">
              <h3>{{orderDetail.product.name}}</h3>
              <div>
                <span>{{$dayjs(orderDetail.created_at).format('YYYY年M月D日')}}</span>
                <span>合計：{{orderDetail.quantity}}点</span>
                <span>\{{orderDetail.price_fixed.toLocaleString()}} (税込)</span>
              </div>
            </div>
            <NuxtLink :to="`/products/${orderDetail.product_id}`" class="again">もう一度購入する</NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',
  data() {
    return {
      orders: null,
    }
  },
  methods: {
    async getOrder() {
      const orders = await this.$axios.get(`http://127.0.0.1:8000/api/order/?user_id=${this.$auth.user.id}`);
      this.orders = orders.data.reverse();
    },
  },
  created() {
    this.getOrder();
  },
}
</script>

<style>
.history {
  width: 65%;
  margin: 0 auto;
  min-height: 90vh;
}

.history h1 {
  font-size: 28px;
  padding: 60px 0 40px;
}

.orders {
  margin-bottom: 100px;
  border-bottom: gainsboro 2px solid;
}

.order-details {
  border-top: gainsboro 2px solid;
  display: flex;
  padding: 15px 0;
}

.order-details__text {
  width: 51vw;
  padding-left: 2vw;
  display: flex;
  flex-flow: column;
  justify-content: space-between;
  color: gray;
}

.order-details__text p {
  font-size: 13px;
}

.order-details__product{
  display: flex;
  justify-content: space-between;
}

.order-details__product h3 {
  color: black;
  margin-right: 4vw;
  font-size: 18px;
}

.order-details__product div {
  display: flex;
  flex-wrap: wrap;
}

.order-details__product span {
  padding-left: 2vw;
  font-size: 15px;
}

.again {
  background: #8aad28;
  color: white;
  border-radius: 30px;
  padding: 3px 0;
  font-size: 14px;
  width: 180px;
  text-align: center;
  margin-left: auto;
}
</style>