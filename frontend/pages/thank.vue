<template>
  <div class="thank">
    <h1>ありがとうございます。注文が確定されました。</h1>
    <p>注文内容と配送状況についてはEメールでお知らせをご確認ください。</p>
    <div class="order-uuid">
      <h3>注文番号</h3>
      <h2>{{$route.query.order_uuid}}</h2>
      <p>で承りました</p>
      <NuxtLink to="/" class="continue">ショッピングを続ける</NuxtLink>
    </div>
  </div>
</template>



<style>
.thank {
  text-align: center;
  height: 90vh;
}

.thank h1 {
  font-size: 32px;
  padding: 100px 0 30px;
}

.thank p {
  margin-bottom: 50px;
}

.order-uuid {
  background: #EEEEEE;
  width: 60%;
  margin: 0 auto;
  padding: 50px 0;
}

.order-uuid h3 {
  font-size: 18px;
  margin-bottom: 30px;
}

.order-uuid h2 {
  font-size: 24px;
  margin-bottom: 40px;
}

.continue {
  display: inline-block;
  background: #8aad28;
  color: white;
  border-radius: 30px;
  width: 90%;
  max-width: 280px;
  padding: 10px 0;
  font-weight: bold;
}
</style>