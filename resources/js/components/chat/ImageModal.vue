<template>
  <div
    class="position-fixed top-50 start-50 translate-middle h-100 w-100 d-flex justify-content-center align-items-center bg-dark text-light"
    v-if="visible"
    @click="hide"
  >
    <img
      v-if="imageUrl"
      :src="'/telegram/files/' + imageUrl"
      alt="full image"
      class="mw-100 mh-100"
    />
    <button
      type="button"
      class="btn-close position-absolute top-0 end-0"
      disabled
      aria-label="Close"
      @click="hide"
    ></button>
  </div>
</template>

<script>
export default {
  created() {
    this.$emit("modal-interface", { show: this.show });
  },
  data() {
    return {
      visible: false,
    };
  },
  props: {
    imageUrl: null,
  },
  methods: {
    show() {
      this.visible = true;
    },
    hide() {
      this.visible = false;
    },
    test() {
      console.log("test");
    },
  },
};
</script>

<style lang="scss" scoped></style>
