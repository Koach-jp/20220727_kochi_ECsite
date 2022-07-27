<template>
  <div class="login-register flex">
    <div class="login">
      <h2>ログイン</h2>
      <h3>会員のお客様</h3>
      <p>メールアドレスとパスワードを入力してログインしてください。</p>
      <validation-observer ref="obs" v-slot="ObserverProps">
        <form @submit.prevent="login">
          <div class="input">
            <validation-provider v-slot="{errors}" rules="required|email">
              <p><label>
                メールアドレス : <input type="email" v-model="email" placeholder="email" name="メールアドレス">
              </label></p>
              <div class="error">{{errors[0]}}</div>
            </validation-provider>
            <validation-provider v-slot="{errors}" rules="required|min:6">
              <p><label>
                パスワード : <input type="password" v-model="password" placeholder="password" name="パスワード">
              </label></p>
              <div class="error">{{errors[0]}}</div>
            </validation-provider>
          </div>
          <button type="submit" :disabled="ObserverProps.invalid || !ObserverProps.validated">ログイン</button>
        </form>
      </validation-observer>
    </div>
    <div class="register">
      <h2>新規会員登録</h2>
      <h3>初めてご利用の方・会員以外の方</h3>
      <p>初めてご利用のお客様は、こちらから会員登録を行ってください。</p>
      <validation-observer ref="obs" v-slot="ObserverProps">
        <form @submit.prevent="register">
          <div class="input">
            <validation-provider v-slot="{errors}" rules="required|email">
              <p><label>
                メールアドレス : <input type="email" v-model="newEmail" placeholder="email" name="メールアドレス">
              </label></p>
              <div class="error">{{errors[0]}}</div>
            </validation-provider>
            <validation-provider v-slot="{errors}" rules="required|min:6">
              <p><label>
                パスワード : <input type="password" v-model="newPassword" placeholder="password" name="パスワード">
              </label></p>
              <div class="error">{{errors[0]}}</div>
            </validation-provider>
            <validation-provider v-slot="{errors}" rules="required|min:4|alpha_dash">
              <p><label>
                お名前 : <input type="text" v-model="newName" placeholder="name" name="お名前">
              </label></p>
              <div class="error">{{errors[0]}}</div>
            </validation-provider>
          </div>
          <button type="submit" :disabled="ObserverProps.invalid || !ObserverProps.validated">新規会員登録</button>
        </form>
      </validation-observer>
    </div>
  </div>
</template>

<script>
export default {
  auth: false,
  data() {
    return {
      email: null,
      password: null,
      newName: null,
      newEmail: null,
      newPassword: null,
    };
  },
  methods: {
    async login() {
      try {
        await this.$auth.loginWith("laravelJWT", {
          data: {
            email: this.email,
            password: this.password,
          }
        });
        this.$router.push("/");
      } catch (err) {
        console.log(err);
        alert('メールアドレスまたはパスワードが間違っているか、ユーザーが存在しないか、サーバーのエラーです。\n詳細：'+err.message);
      }
    },

    async register() {
      try {
        const profile = {
          name: this.newName,
          email: this.newEmail,
          password: this.newPassword,
        };
        await this.$axios.post("http://localhost:8000/api/auth/register", profile);
        this.email = this.newEmail;
        this.password = this.newPassword;
        this.login();
      } catch (err) {
        console.log(err);
        alert('このメールアドレスは使用済みです。\n詳細：'+err.message);
      }
    },
  },
};
</script>

<style>
.login-register {
  width: 90%;
  margin: 0 auto;
  height: 90vh;
  align-items: flex-start;
  justify-content: center;
  padding-top: 22vh;
}

.login, .register {
  padding: 20px;
}

.login-register h2 {
  font-size: 30px;
  font-weight: normal;
  margin-bottom: 30px;
}

.login-register h3 {
  font-size: 20px;
  border-bottom: black 2px solid;
  margin-bottom: 20px;
}

.login-register form {
  margin-top: 20px;
  font-weight: bold;
  text-align: right;
  height: 180px;
  display: flex;
  flex-flow: column;
  justify-content: space-between;
}

.login-register input {
  line-height: 1.5;
  margin: 5px 0;
  width: 60%;
}

.login-register button {
  width: 170px;
  margin: 0 auto;
  color: white;
  border: none;
  padding: 3px;
}

.login button {
  background: black;
}

.register button {
  background: #8aad28;
}

.error {
  color: red;
  font-size: 12px;
}
</style>