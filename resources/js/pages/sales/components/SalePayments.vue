<template>
  <v-simple-table dense>
    <template #default>
      <thead>
        <tr>
          <th v-if="edit" style="width:100px" />
          <th style="width:160px">
            Date
          </th>
          <th>Description</th>
          <th>Reference</th>
          <th style="width:150px">
            Mode
          </th>
          <th style="width:100px">
            Amount
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="item in salePayments"
          :key="item.id"
        >
          <td v-if="edit">
            <v-btn
              class="ma-2"
              text
              icon
              color="red"
              @click="removeItem(item.id)"
            >
              <v-icon>
                mdi-delete
              </v-icon>
            </v-btn>
          </td>
          <td>{{ item.paymentTime || '-' }}</td>
          <td>{{ item.description || '-' }}</td>
          <td>{{ item.reference }}</td>
          <td>{{ item.paymentMode }}</td>
          <td>{{ item.amount | currency }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
export default {
  props: {
    salePayments: {
      type: Array,
      default () {
        return []
      }
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    removeItem (paymentId) {
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
            this.$emit('paymentRemoved', true)
          })
        }
      })
    }
  }
}
</script>
