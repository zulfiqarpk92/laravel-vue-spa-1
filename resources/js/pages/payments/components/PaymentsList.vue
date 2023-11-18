<template>
  <v-card
    rounded="lg"
  >
    <v-card-title>Payments</v-card-title>
    <v-card-text
      class="d-flex align-center flex-wrap"
    >
      <v-text-field
        v-model="searchText"
        dense
        hide-details
        placeholder="Search"
        class="search-input me-3"
        @keyup.enter="loadData"
      />
      <user-lookup
        v-if="!userId"
        user-type="User"
        class="search-input me-3"
        @selected="selectUser"
      />
      <v-spacer />
      <div class="d-flex align-center flex-wrap">
        <v-btn
          color="primary"
          @click="paymentDialog = true"
        >
          <v-icon>
            mdi-plus
          </v-icon>
          New Payment
        </v-btn>
      </div>
    </v-card-text>
    <v-data-table
      :headers="visibleHeaders"
      :items="items"
      :options.sync="options"
      :server-items-length="totalItems"
      :loading="loading"
      item-key="name"
    >
      <template #item.user="{ item }">
        {{ item.user }}
      </template>
      <template #item.amount="{ item }">
        {{ item.amount | currency }}
      </template>
      <template #item.actions="{ item }">
        <v-icon
          small
          @click="$router.push({ name : 'sales.view', params: { saleId: item.id } })"
        >
          mdi-eye
        </v-icon>
        <v-icon
          small
          @click="$router.push({ name : 'sales.edit', params: { saleId: item.id } })"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          color="red"
          @click="deletePayment(item.id)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
    <payment-modal v-model="paymentDialog" @reload="loadData" />
  </v-card>
</template>

<script>
import PaymentModal from './PaymentModal.vue'

export default {
  components: { PaymentModal },
  props: {
    userId: {
      type: [String, Number],
      default: 0
    }
  },
  data () {
    return {
      totalItems: 0,
      loading: true,
      options: {},
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Date', value: 'paymentTime' },
        { text: 'User', value: 'user' },
        { text: 'Mode', value: 'paymentMode' },
        { text: 'Linked', value: 'linkedTo' },
        { text: 'Total', value: 'amount' },
        { text: 'Created At', value: 'createdAt' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      items: [],
      searchText: '',
      user: 0,
      paymentDialog: false
    }
  },
  computed: {
    visibleHeaders () {
      const hideHeaders = []
      if (this.userId) {
        hideHeaders.push('user')
      }
      return this.headers.filter(h => !hideHeaders.includes(h.value))
    }
  },
  watch: {
    options: {
      handler () {
        this.loadData()
      },
      deep: true
    }
  },
  mounted () {
    if (this.userId) {
      this.user = this.userId
    }
  },
  methods: {
    selectUser: function (user) {
      this.user = user.id || 0
      this.loadData()
    },
    loadData: function () {
      this.loading = true
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      this.$http.get(`/api/payments?per_page=${itemsPerPage}&page=${page}&orderBy=${sortBy}&orderDesc=${sortDesc}&q=${this.searchText}&userId=${this.user}`)
        .then(response => {
          const { meta, data } = response.data
          this.totalItems = meta && meta.total
          this.items = data || []
          this.loading = false
        })
        .catch(error => {
          this.totalItems = 0
          this.loading = false
          if (error.response && error.response.data) {
            this.$set(this.form, 'errorMessage', error.response.data.message)
          }
          console.log(error)
        })
    },
    deletePayment: function (paymentId) {
      this.$confirm.fire({
        icon: 'warning',
        title: 'Delete Confirm',
        text: 'Are you sure you want to delete this payment?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$http.delete(`/api/payments/${paymentId}`).then(({ data }) => {
            this.$store.dispatch('snackbar/showMessage', data.message)
            this.loadData()
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.search-input{
  max-width: 200px;
}
</style>
