
<template>
  <v-menu
    offset-y
    left
    nudge-bottom="14"
    min-width="230"
    content-class="user-profile-menu-content"
  >
    <template #activator="{ on, attrs }">
      <v-badge
        bottom
        color="success"
        overlap
        offset-x="12"
        offset-y="12"
        class="ms-4"
        dot
      >
        <v-avatar
          size="40px"
          color="grey darken-1"
          v-bind="attrs"
          v-on="on"
        >
          <!-- <v-img :src="require('@/assets/images/avatars/1.png')" /> -->
        </v-avatar>
      </v-badge>
    </template>
    <v-list>
      <div class="pb-3 pt-2">
        <v-badge
          bottom
          color="success"
          overlap
          offset-x="12"
          offset-y="12"
          class="ms-4"
          dot
        >
          <v-avatar
            size="40px"
            color="grey darken-1"
          >
            <!-- <v-img :src="require('@/assets/images/avatars/1.png')" /> -->
          </v-avatar>
        </v-badge>
        <div
          class="d-inline-flex flex-column justify-center ms-3"
          style="vertical-align:middle"
        >
          <span class="text--primary font-weight-semibold mb-n1">
            {{ user.name }}
          </span>
          <small class="text--disabled text-capitalize">Admin</small>
        </div>
      </div>

      <v-divider />

      <!-- Profile -->
      <v-list-item link>
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            mdi-account-outline
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <!-- Settings -->
      <v-list-item link>
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            mdi-cog-outline
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-divider class="mb-2" />

      <!-- Logout -->
      <v-list-item link @click="$emit('logout')">
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            mdi-logout-variant
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>

export default {
  props: ['user']
}
</script>

<style lang="scss">
.user-profile-menu-content {
  .v-list-item {
    min-height: 2.5rem !important;
  }
}
</style>
