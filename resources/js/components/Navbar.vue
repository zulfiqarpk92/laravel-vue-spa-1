<template>
  <div class="d-print-none">
    <v-app-bar
      app
      color="white"
      flat
    >
      <v-container v-if="user" class="py-0 fill-height admin-container flex justify-space-between" fluid>
        <v-avatar
          class="mr-10"
          color="blue darken-1"
          size="48"
        />
        <v-btn
          text
          rounded
          large
          to="/home"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-home
          </v-icon>
          Dashboard
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'customers' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-account
          </v-icon>
          Customers
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'suppliers' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-account
          </v-icon>
          Suppliers
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'employees' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-account
          </v-icon>
          Employees
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'items' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-view-list
          </v-icon>
          Items
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'sales' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-receipt
          </v-icon>
          Sales
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'payments' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-currency-usd
          </v-icon>
          Payments
        </v-btn>
        <v-btn
          text
          rounded
          large
          :to="{ name: 'purchases' }"
          type="button"
          class="text-capitalize text-base font-weight-regular px-6"
        >
          <v-icon left>
            mdi-receipt
          </v-icon>
          Purchases
        </v-btn>

        <user-menu-vue :user="user" @logout="logout" />
      </v-container>
    </v-app-bar>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import UserMenuVue from './UserMenu.vue'

export default {
  components: {
    UserMenuVue
  },

  data: () => ({
    isDropdownOpen: false
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    },
    toggleDropdown () {
      this.isDropdownOpen = !this.isDropdownOpen
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
.v-btn--active{
  color:#2b81d6;
}
.user-profile-menu-content{
  margin-top:50px;
}
</style>
