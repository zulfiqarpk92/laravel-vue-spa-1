<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card
        height="100%"
        width="100%"
        class="ml-auto py-8 px-6"
      >
        <v-card-title class="d-flex align-center justify-center py-7">
          <router-link
            to="/"
            class="d-flex align-center"
          >
            <v-img
              src="https://picsum.photos/id/11/200/200"
              max-height="30px"
              max-width="30px"
              alt="logo"
              contain
              class="me-3 "
            />

            <h2 class="text-2xl font-weight-semibold">
              Xyz
            </h2>
          </router-link>
        </v-card-title>

        <!-- title -->
        <v-card-text>
          <p class="text-2xl font-weight-semibold text--primary">
            Welcome to XYZ!
          </p>
          <p class="mb">
            Please sign-in to your account and start the adventure
          </p>
        </v-card-text>

        <v-form @submit.prevent="login" @keydown="form.onKeydown($event)">
          <v-card-text>
            <v-text-field
              v-model="form.email"
              class="pt-4"
              outlined
              prepend-icon="mdi-account-circle"
              :error-messages="form.errors.get('email')"
              :label="$t('email')"
              required
            />
            <v-text-field
              v-model="form.password"
              class="pt-4"
              outlined
              prepend-icon="mdi-lock"
              :error-messages="form.errors.get('password')"
              :label="$t('password')"
              type="password"
              required
            />
          </v-card-text>
          <v-divider />
          <v-checkbox label="Remember me" class="ml-4" />
          <v-card-actions>
            <v-btn class="pt-4 pb-4" block type="submit" color="primary" :loading="form.busy">
              {{ $t("login") }}
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Cookies from 'js-cookie'

export default {
  components: {
  },
  layout: 'basic',

  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    config: window.config,
    remember: false
  }),

  methods: {
    async login () {
      try {
        // Submit the form.
        const { data } = await this.form.post('/api/login')

        // Save the token.
        this.$store.dispatch('auth/saveToken', {
          token: data.token,
          remember: this.remember
        })

        // Fetch the user.
        await this.$store.dispatch('auth/fetchUser')

        // Redirect home.
        this.redirect()
      } catch (ex) {
        console.log(ex)
      }
    },

    redirect () {
      const intendedUrl = Cookies.get('intended_url')

      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
    },

    fillWithAdmin () {
      this.form.fill({
        email: 'admin@example.com',
        password: '123456'
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.auth-wrapper {
  display: flex;
  min-height: calc(var(--vh, 1vh) * 100);
  width: 100%;
  flex-basis: 100%;
  align-items: center;

  // common style for both v1 and v2
  a {
    text-decoration: unset;
  }

  // auth v1
  &.auth-v1 {
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 1.5rem;

    .auth-mask-bg {
      position: absolute;
      bottom: 0;
      width: 100%;
    }
    .auth-tree,
    .auth-tree-3 {
      position: absolute;
    }
    .auth-tree {
      bottom: 0;
      left: 0;
    }
    .auth-tree-3 {
      bottom: 0;
      right: 0;
    }

    .auth-inner {
      width: 28rem;
      z-index: 1;
      .auth-card {
        padding: 0.9375rem 0.875rem;
      }
    }
  }
}

@media (max-width: 600px) {
  .auth-v1 {
    .auth-tree,
    .auth-tree-3,
    .auth-mask-bg {
      display: none;
    }
  }
}
</style>
