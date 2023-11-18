<template>
  <v-card @click="loadData">
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
            Purchase # {{ purchase.id }}
          </p>
          <p class="mb-2">
            <span>Date: </span>
            <span class="font-weight-semibold">{{ purchase.purchaseTime }}</span>
          </p>
        </div>
      </div>
    </v-card-text>
    <v-divider />
    <v-card-text class="py-9 px-8">
      <div class="payment-details d-flex justify-space-between flex-wrap flex-column flex-sm-row">
        <div v-if="purchase.supplier" class="mb-8 mb-sm-0">
          <p class="font-weight-semibold payment-details-header">
            Supplier:
          </p>
          <p class="mb-1">
            {{ purchase.supplier.name }}
          </p>
          <p v-if="purchase.supplier.companyName" class="mb-1">
            {{ purchase.supplier.companyName }}
          </p>
          <p v-if="purchase.supplier.address" class="mb-1">
            {{ purchase.supplier.address }}
          </p>
          <p v-if="purchase.supplier.city || purchase.supplier.province" class="mb-1">
            {{ purchase.supplier.city }}, {{ purchase.supplier.province }}
          </p>
          <p v-if="purchase.supplier.phone" class="mb-1">
            {{ purchase.supplier.phone }}
          </p>
          <p v-if="purchase.supplier.email" class="mb-1">
            {{ purchase.supplier.email }}
          </p>
        </div>
        <div v-if="false">
          <p class="font-weight-semibold payment-details-header">
            Bill To:
          </p>
          <table>
            <tr>
              <td class="pe-6">
                Total Due:
              </td>
              <td>$12,110.55</td>
            </tr>
            <tr>
              <td class="pe-6">
                Bank Name:
              </td>
              <td>American Bank</td>
            </tr>
            <tr>
              <td class="pe-6">
                Country:
              </td>
              <td>United States</td>
            </tr>
            <tr>
              <td class="pe-6">
                IBAN:
              </td>
              <td>ETD95476213874685</td>
            </tr>
            <tr>
              <td class="pe-6">
                SWIFT Code:
              </td>
              <td>BR91905</td>
            </tr>
          </table>
        </div>
      </div>
    </v-card-text>
    <invoice-items :invoice-items="purchase.items" />
    <v-card-text class="py-9 px-8">
      <div class="invoice-total d-flex justify-space-between flex-column flex-sm-row">
        <div class="mb-2 mb-sm-0">
          <p class="mb-1">
            <span class="font-weight-semibold">Salesperson: </span> <span>{{ purchase.createdBy }}</span>
          </p>
          <p>Thanks for your business</p>
          <p><strong>Comments: </strong> {{ purchase.comments }}</p>
        </div>
        <div>
          <table>
            <tr>
              <td class="pe-16">
                Total:
              </td>
              <th class="text-right">
                {{ purchase.totalAmount | currency }}
              </th>
            </tr>
            <tr>
              <td class="pe-16">
                Payment:
              </td>
              <th class="text-right">
                {{ purchase.amountPaid | currency }}
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
                {{ purchase.balance | currency }}
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
</template>

<script>
import InvoiceItems from './components/InvoiceItems.vue'

export default {
  components: { InvoiceItems },
  middleware: 'auth',
  props: {
    purchaseId: {
      type: String,
      default: ''
    }
  },
  data: function () {
    return {
      loading: false,
      purchase: {}
    }
  },
  watch: {
  },
  mounted () {
    if (this.purchaseId) {
      this.loadData()
    }
  },
  methods: {
    loadData: function () {
      this.loading = true
      this.$http
        .get('/api/purchases/' + this.purchaseId)
        .then(({ data }) => {
          this.purchase = data.data
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
