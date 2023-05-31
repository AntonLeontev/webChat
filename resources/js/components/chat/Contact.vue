<template>
  <li class="active">
    <div class="d-flex bd-highlight">
      <div class="img_cont">
        <img
          class="rounded-circle user_img"
          src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
        />
        <span class="unread_icon"></span>
      </div>
      <div class="user_info">
        <span class="d-block text-truncate">Khalid long long long</span>
        <p>Kalid is online</p>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  created() {},
  data() {
    return {};
  },
  props: {
    user: Object,
    selectedUserId: Number,
  },
  methods: {},
};
</script>

<style lang="scss" scoped></style>
