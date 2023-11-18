<template>
  <v-row>
    <v-col
      cols="12"
      md="9"
    >
      <v-card>
        <v-card-text class="py-9 px-8">
          <div class="d-flex flex-wrap justify-space-between flex-column flex-sm-row">
            <div class="mb-8 mb-sm-0">
              <div class="d-flex align-center mb-6">
                <v-img
                  lazy-src="https://picsum.photos/id/11/10/6"
                  max-height="30"
                  max-width="30"
                  src="https://picsum.photos/id/11/500/300"
                />
                <span class="text--primary font-weight-bold text-xl ml-6"> {{ $settings.company }} </span>
              </div>
              <div class="d-block">
                {{ $settings.address }}
              </div>
              <div class="d-block">
                <strong>Phone: </strong>{{ $settings.phone }}
              </div>
              <div class="d-block">
                <strong>Email: </strong>{{ $settings.email }}
              </div>
              <div class="d-block">
                <strong>Website: </strong>{{ $settings.website }}
              </div>
            </div>
            <div>
              <p class="font-weight-medium text-xl text--primary mb-4">
                Invoice # {{ sale.id }}
              </p>
              <p class="mb-2">
                <span>Date Issued: </span>
                <span class="font-weight-semibold">{{ sale.saleTime }}</span>
              </p>
              <p class="mb-2">
                <span>Due Date: </span>
                <span class="font-weight-semibold">{{ sale.dueDate }}1</span>
              </p>
            </div>
          </div>
        </v-card-text>
        <v-divider />
        <v-card-text class="py-9 px-8">
          <div class="payment-details d-flex justify-space-between flex-wrap flex-column flex-sm-row">
            <div v-if="sale.customer" class="mb-8 mb-sm-0">
              <p class="font-weight-semibold payment-details-header">
                Invoice To:
              </p>
              <p class="mb-1">
                {{ sale.customer.name }}
              </p>
              <p v-if="sale.customer.companyName" class="mb-1">
                {{ sale.customer.companyName }}
              </p>
              <p v-if="sale.customer.address" class="mb-1">
                {{ sale.customer.address }}
              </p>
              <p v-if="sale.customer.city || sale.customer.province" class="mb-1">
                {{ sale.customer.city }}, {{ sale.customer.province }}
              </p>
              <p v-if="sale.customer.phone" class="mb-1">
                {{ sale.customer.phone }}
              </p>
              <p v-if="sale.customer.email" class="mb-1">
                {{ sale.customer.email }}
              </p>
            </div>
          </div>
        </v-card-text>
        <invoice-items :invoice-items="sale.items" />
        <v-divider class="mb-2" />
        <h5 class="ml-6 mb-2">
          Payments
        </h5>
        <sale-payments :sale-payments="sale.payments" />
        <v-card-text class="py-9 px-8">
          <div class="invoice-total d-flex justify-space-between flex-column flex-sm-row">
            <div class="mb-2 mb-sm-0">
              <p class="mb-1">
                <span class="font-weight-semibold">Salesperson: </span> <span>{{ sale.createdBy }}</span>
              </p>
              <p>Thanks for your business</p>
              <p><strong>Comments: </strong> {{ sale.comments }}</p>
            </div>
            <div>
              <table>
                <tr>
                  <td class="pe-16">
                    Total:
                  </td>
                  <th class="text-right">
                    {{ sale.totalAmount | currency }}
                  </th>
                </tr>
                <tr>
                  <td class="pe-16">
                    Payment:
                  </td>
                  <th class="text-right">
                    {{ sale.amountPaid | currency }}
                  </th>
                </tr>
              </table>
              <hr role="separator" aria-orientation="horizontal" class="mt-4 mb-3 v-divider theme--light">
              <table class="w-full">
                <tr>
                  <td class="pe-16">
                    Balance:
                  </td>
                  <th class="text-right">
                    {{ sale.balance | currency }}
                  </th>
                </tr>
              </table>
            </div>
          </div>
        </v-card-text>
        <v-divider />
        <v-card-text class="py-9 px-8">
          <p class="mb-0">
            <span class="font-weight-semibold">Return Policy: </span><span>{{ $settings.return_policy }}</span>
          </p>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" md="3">
      <v-card class="invoice-options">
        <v-card-text>
          <v-btn v-if="sale.customerId" class="mb-3" block color="primary" :to="{ name : 'customers.edit', params: { customerId: sale.customerId } }">
            Customer Account
          </v-btn>
          <v-btn class="mb-3" block color="primary" @click="loadData">
            Refresh
          </v-btn>
          <v-btn class="mb-3" block color="primary" :to="{ name : 'sales.edit', params: { saleId: sale.id } }">
            Edit
          </v-btn>
          <v-btn class="mb-3" block color="primary" @click="paymentDialog = true">
            Add Payment
          </v-btn>
          <v-btn class="mb-3" block color="error" to="/sales">
            Cancel
          </v-btn>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import InvoiceItems from './components/InvoiceItems.vue'
import SalePayments from './components/SalePayments.vue'

export default {
  name: 'ViewSale',
  components: { InvoiceItems, SalePayments },
  middleware: 'auth',
  props: {
    saleId: {
      type: [String, Number],
      default: ''
    }
  },
  data: function () {
    return {
      loading: false,
      sale: {}
    }
  },
  mounted () {
    if (this.saleId) {
      this.loadData()
    }
  },
  methods: {
    loadData: function () {
      this.loading = true
      this.$http
        .get('/api/sales/' + this.saleId)
        .then(({ data }) => {
          this.sale = data.data
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
