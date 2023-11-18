<template>
  <v-menu
    v-model="popupModal"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template #activator="{ on, attrs }">
      <v-text-field
        v-model="currentValue"
        :error-messages="errorMessage"
        :label="label"
        prepend-icon="mdi-calendar"
        readonly
        v-bind="attrs"
        v-on="on"
      />
    </template>
    <v-date-picker
      v-model="currentValue"
      scrollable
      @input="handleInput"
    />
  </v-menu>
</template>

<script>
export default {
  props: ['label', 'value', 'errorMessage'],
  data: function () {
    return {
      popupModal: false,
      currentValue: this.value
    }
  },
  watch: {
    value (newVal, oldVal) {
      if (newVal !== oldVal) {
        this.currentValue = newVal
      }
    }
  },
  methods: {
    handleInput () {
      this.$emit('input', this.currentValue)
      this.popupModal = false
    }
  }
}
</script>
