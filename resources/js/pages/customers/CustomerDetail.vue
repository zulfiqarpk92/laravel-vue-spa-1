<template>
  <v-row v-if="customerInfo">
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
    <v-col lg="8" md="7">
      <v-tabs
        v-model="currentTab"
        centered
        background-color="transparent"
      >
        <v-tab>Invoices</v-tab>
        <v-tab>Payments</v-tab>
      </v-tabs>
      <v-tabs-items v-model="currentTab" class="mt-5 pa-1" style="background-color: transparent;">
        <v-tab-item>
          <v-card>
            <v-card-title class="justify-space-between">
              <span>Invoices</span>
              <v-btn color="primary" to="/sales/add">
                Add Invoice
              </v-btn>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="invoices"
              item-key="name"
              class="elevation-1"
            >
              <template #item.actions="{ item }">
                <v-icon
                  small
                  class="mr-2"
                  label="edit"
                  @click="$router.push({ name : 'customers.edit', params: { customerId: item.id } })"
                >
                  mdi-pencil
                </v-icon>
                <v-icon
                  small
                  @click="deleteCustomer(item)"
                >
                  mdi-delete
                </v-icon>
              </template>
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card>
            <v-card-title class="justify-space-between">
              <span>Payments</span>
              <v-btn color="primary" @click="showPaymentModal = true">
                Add Payment
              </v-btn>
            </v-card-title>
            <v-data-table
              :headers="paymentsHeaders"
              :items="payments"
              item-key="id"
              class="elevation-1"
            >
              <template #item.actions="{ item }">
                <v-icon
                  small
                  class="mr-2"
                  label="edit"
                  @click="$router.push({ name : 'customers.edit', params: { customerId: item.id } })"
                >
                  mdi-pencil
                </v-icon>
                <v-icon
                  small
                  @click="deletePayment(item)"
                >
                  mdi-delete
                </v-icon>
              </template>
            </v-data-table>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-col>
    <v-dialog
      v-model="showPaymentModal"
      persistent
      max-width="500px"
    >
      <v-card>
        <v-card-title>
          <span class="text-h5">Add Payment</span>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="payment.description"
                :error-messages="payment.errors.get('description')"
                label="Description"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="payment.amount"
                :error-messages="payment.errors.get('amount')"
                label="Amount"
                required
              />
            </v-col>
          </v-row>
          <small class="red--text">* indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="blue darken-1"
            text
            @click="showPaymentModal = false"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="addPayment"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import Form from 'vform'
import CustomerInfo from './components/CustomerInfo.vue'
export default {
  components: { CustomerInfo },
  middleware: 'auth',
  props: { customerId: { type: String, default: '0' } },
  data () {
    return {
      showPaymentModal: false,
      payment: new Form({
        user_id: this.customerId,
        description: '',
        amount: 0
      }),
      currentTab: null,
      customerInfo: null,
      headers: [
        {
          text: 'ID',
          align: 'start',
          sortable: false,
          value: 'id'
        },
        { text: 'Date', value: 'sale_time' },
        { text: 'Qty', value: 'total_amount' },
        { text: 'Total', value: 'total_amount' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      paymentsHeaders: [
        {
          text: 'ID',
          align: 'start',
          sortable: false,
          value: 'id'
        },
        { text: 'Date', value: 'created_at' },
        { text: 'Description', value: 'description' },
        { text: 'Amount', value: 'amount' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      invoices: [],
      payments: []
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
        .get('/api/customers/' + this.customerId + '?include[]=sales&include[]=payments')
        .then(({ data }) => {
          this.customerInfo = data.data
          this.invoices = data.data.invoices
          this.payments = data.data.payments
          this.loading = false
        })
        .catch(error => {
          console.log(error)
          this.loading = false
        })
    },
    addPayment: function () {
      this.payment.post('/api/payments')
        .then(() => {
          this.loadData()
          this.showPaymentModal = false
        })
    },
    deleteSale: function (invoice) {
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
          this.$http.delete(`/api/sales/${invoice.id}`).then(({ data }) => {
            this.$store.dispatch('snackbar/showMessage', data.message)
            this.loadData()
          })
        }
      })
    },
    deletePayment: function (payment) {
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
          this.$http.delete(`/api/payments/${payment.id}`).then(({ data }) => {
            this.$store.dispatch('snackbar/showMessage', data.message)
            this.loadData()
          })
        }
      })
    }
  }
}
</script>
