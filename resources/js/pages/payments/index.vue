<template>
  <payments-list />
</template>

<script>
import PaymentsList from './components/PaymentsList.vue'

export default {
  components: { PaymentsList },
  middleware: 'auth'
}
</script>
