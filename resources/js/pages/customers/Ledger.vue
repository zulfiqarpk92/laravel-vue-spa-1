<template>
  <div>
    <v-card v-if="customerInfo" rounded="lg">
      <v-card-title class="justify-space-between">
        <div>
          {{ customerInfo.name }} Ledger
        </div>

        <v-btn color="primary" class="d-print-none" @click="printReport">
          Print Report
        </v-btn>
      </v-card-title>
      <v-row>
        <v-col class="px-4">
          <v-simple-table dense class="ledger">
            <thead>
              <tr>
                <th class="text-left" style="width: 120px;">
                  Date
                </th>
                <th class="text-left" />
                <th class="text-left" />
                <th class="text-right" style="width: 120px;">
                  Debit
                </th>
                <th class="text-right" style="width: 120px;">
                  Credit
                </th>
                <th class="text-right" style="width: 120px;">
                  Balance
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in ledger" :key="item.id">
                <td>{{ item.ref_date }}</td>
                <td colspan="2" class="px-0">
                  <div v-if="item.type === 'opening'" class="opening-balance">
                    ****** Opening Balance ******
                  </div>
                  <table v-if="['sale'].includes(item.type)" class="invoice-items">
                    <tr>
                      <td colspan="4" class="pl-0">
                        <div class="invoice-info">
                          <div>INV- {{ item.id }} </div>
                          <div v-if="item.type === 'sale'">
                            Sale of items on Inv # {{ item.id }} Note: {{ item.comments }}
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="invoice_item in item.items" :key="invoice_item.id">
                      <td class="item-name">
                        {{ invoice_item.name }}
                      </td>
                      <td class="item-quantity">
                        {{ invoice_item.quantity | showNumber }}
                      </td>
                      <td class="item-price">
                        {{ invoice_item.price | currency }}
                      </td>
                      <td class="item-total">
                        {{ invoice_item.total | currency }}
                      </td>
                    </tr>
                  </table>
                  <div v-if="['payment'].includes(item.type)" class="invoice-info">
                    <div> PY- {{ item.id }} </div>
                    <div>
                      Payment received: {{ item.description }}
                    </div>
                  </div>
                </td>
                <td class="text-right">
                  <span v-if="item.type === 'sale'">{{ item.tendered_amount | currency }}</span>
                </td>
                <td class="text-right">
                  <span v-if="item.type === 'payment'">{{ item.tendered_amount | currency }}</span>
                </td>
                <td class="text-right" :class="{ 'font-weight-black': item.type === 'opening' }">
                  {{ (item.type === 'payment' ? (runningBalance -= item.tendered_amount) : (runningBalance += item.tendered_amount)) | currency }}
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td />
                <td />
                <td />
                <td class="text-right">
                  {{ totalDebit | currency }}
                </td>
                <td class="text-right">
                  {{ totalCredit | currency }}
                </td>
                <td class="text-right" />
              </tr>
            </tfoot>
          </v-simple-table>
        <!-- <pre>{{ ledger }}</pre> -->
        </v-col>
      </v-row>
      <v-row>
        <v-col class="px-4 py-8 closing-balance">
          <div class="pl-5">
            Closing Balance:
          </div>
          <div>{{ closingBalance | currency }}</div>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
export default {
  layout: 'default',
  middleware: 'auth',
  props: { customerId: { type: [String, Number], default: '0' } },
  data () {
    return {
      customerInfo: null,
      ledger: null,
      runningCredit: 0,
      runningBalance: 0
    }
  },
  computed: {
    totalDebit () {
      return this.ledger.reduce(function (total, row) {
        if (row.type !== 'sale') {
          return total
        }
        return total + row.tendered_amount
      }, 0)
    },
    totalCredit () {
      return this.ledger.reduce(function (total, row) {
        if (row.type !== 'payment') {
          return total
        }
        return total + row.tendered_amount
      }, 0)
    },
    closingBalance () {
      return this.runningBalance
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
        .get('/api/customers/' + this.customerId + '/ledger?include[]=sales&include[]=payments')
        .then(({ data }) => {
          console.log(data)
          this.customerInfo = data.customerInfo
          this.ledger = data.ledger
          this.loading = false
        })
        .catch(error => {
          console.log(error)
          this.loading = false
        })
    },
    printReport: function () {
      this.$router.push()
      this.$router.app.setLayout('print')
      setTimeout(() => window.print(), 2000)
      // window.print()
    }
  }
}
</script>

<style lang="scss">
.ledger {
  border-collapse: collapse;

  tbody>tr:first-child>td {
    border-top: 1px solid #000000;
  }

  tr>td {
    border-bottom: 1px solid #000000 !important;
    border-left: 1px solid #000000;
    vertical-align: top;

    &:last-child {
      border-right: 1px solid #000000;
    }
  }

  .opening-balance {
    font-weight: bold;
    text-align: center;
  }
}

.invoice-info {
  display: flex;
  height: 100%;

  div{
    display: flex;
    align-items: center;
  }
  div:first-child {
    width: 200px;
    border-right: 1px solid #000000;
    padding-left: 8px;
  }

  div:last-child {
    padding-left: 8px;
    flex-grow: 1;
  }
}

.invoice-items {
  width: 100%;
  border-collapse: collapse;

  tr {
    td {
      border: none !important;
      padding: 0 8px;

      &:not(:last-child) {
        border-right: 1px solid #000000 !important;
      }
    }

    &:not(:last-child) {
      td {
        border-bottom: 1px solid #000000 !important;
      }
    }
  }

  .item-name {
    // width: 200px;
  }

  .item-quantity,
  .item-price,
  .item-total {
    width: 120px;
    text-align: right;
  }
}

.closing-balance {
  display: flex;
  font-weight: bold;
  font-size: 2rem;
  padding: 0 16px;

  div:first-child {
    width: 400px;
  }

  div:last-child {
    flex-grow: 1;
    border-top: 1px solid #000000;
    border-bottom: 1px solid #000000;
    text-align: right;
    padding-right: 8px;
  }
}
</style>
