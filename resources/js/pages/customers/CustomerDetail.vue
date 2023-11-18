<template>
  <v-row>
    <v-col lg="4" md="5">
      <v-row>
        <v-col cols="12">
          <customer-info
            :customer-info="customerInfo"
            @customerUpdated="loadData"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col v-if="customerInfo" lg="8" md="7">
      <v-tabs
        v-model="currentTab"
        centered
        background-color="transparent"
      >
        <v-tab>Sales</v-tab>
        <v-tab>Payments</v-tab>
        <v-tab>Purchases</v-tab>
      </v-tabs>
      <v-tabs-items v-model="currentTab" class="mt-5 pa-1" style="background-color: transparent;">
        <v-tab-item>
          <sales-list :customer-id="customerId" />
        </v-tab-item>
        <v-tab-item>
          <payments-list :user-id="customerId" />
        </v-tab-item>
        <v-tab-item>
          <purchases-list :supplier-id="customerId" />
        </v-tab-item>
      </v-tabs-items>
    </v-col>
    <v-col v-else lg="8" md="7">
      <v-skeleton-loader
        class="mx-auto"
        type="table"
      />
    </v-col>
  </v-row>
</template>

<script>
import CustomerInfo from './components/CustomerInfo.vue'
import SalesList from '../sales/components/SalesList.vue'
import PaymentsList from '../payments/components/PaymentsList.vue'
import PurchasesList from '../purchases/components/PurchasesList.vue'

export default {
  components: { CustomerInfo, SalesList, PaymentsList, PurchasesList },
  middleware: 'auth',
  props: { customerId: { type: [String, Number], default: '0' } },
  data () {
    return {
      currentTab: null,
      customerInfo: null
    }
  },
  mounted () {
    if (this.customerId) {
      this.loadData()
    }
  },
  methods: {
    loadData: function () {
      this.loading = true
      this.$http
        .get('/api/customers/' + this.customerId)
        .then(({ data }) => {
          this.customerInfo = data.data
          this.loading = false
        })
        .catch(error => {
          console.log(error)
          this.loading = false
        })
    }
  }
}
</script>
