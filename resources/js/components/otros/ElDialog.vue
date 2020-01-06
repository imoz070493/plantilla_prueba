<template>
  <b-modal
    class="'sdsds'"
    ref="modal"
    :size="size"
    :title="title"
    scrollable
    :centered="true"
    :visible="visible_lock"
    @show="onShowModal"
    @hidden="onHide"
    @ok="handleOk"
    @cancel="handleCancel"
    @close="handleClose"
    @hide="handleHide"
    :hide-header-close="visible_lock"
    :no-close-on-esc="visible_lock"
    :no-close-on-backdrop="visible_lock"
    :header-bg-variant="visible_lock?'primary':'dark'"
    headerTextVariant="light"
    footerBgVariant="light"
    footerTextVariant="dark"
    button-size="sm"
  >
    <template v-slot:modal-header="{ close }">
      <slot name="header" v-slot:header></slot>
    </template>
    <b-container>
      <slot></slot>
    </b-container>
    <template v-slot:modal-footer>
      <slot name="footer"></slot>
    </template>
  </b-modal>
</template>
<script>
export default {
  props: {
    title: {
      type: String,
      default: ""
    },
    size: {
      type: String,
      default: "xl"
    },
    visible_lock: {
      type: Boolean,
      default: false
    },
    cancel: {
      type: Function,
      default: function() {
        return true;
      }
    },
    close: {
      type: Function,
      default: function() {
        return true;
      }
    },
    okFunction: {
      type: Function,
      default: function() {
        return true;
      }
    }
  },
  data() {
    return {};
  },
  watch: {
    visible_lock: function(nv, pv) {
      if (!nv) {
        this.hide();
      } else {
        if (pv != nv) {
          this.show();
        }
      }
    }
  },
  methods: {
    hide() {
      this.$nextTick(() => {
        this.$refs.modal.hide();
      });
    },
    show() {
      console.log("abrir");
      this.$nextTick(() => {
        this.$refs.modal.show();
      });
    },
    onShowModal() {
      console.log("showModal");
      return false;
    },
    handleOk(bvModalEvt) {
      console.log("handleOk");
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      if (this.okFunction() === false) return;
      this.hide();
    },
    handleCancel(bvModalEvt) {
      console.log("handleCancel");
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      if (this.cancel() === false) return;
      this.hide();
    },
    handleClose(bvModalEvt) {
      console.log("handleClose");
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      if (this.close() === false) return;
      this.hide();
    },
    handleHide(bvModalEvt) {
      if (this.visible_lock) {
        console.log("locked!");
        bvModalEvt.preventDefault();
        return;
      }
      console.log("handleHide");
    },

    onHide(bvModalEvt) {
      console.log("onHide");
    },
    checkFormValidity() {
      return this.visible_lock;
    },
    handleSubmit() {
      console.log("handleSubmit");
      // Exit when the form isn't valid
      if (this.checkFormValidity()) {
        return;
      }
      // Hide the modal manually
      this.hide();
    }
  }
};
</script>visible_lock
<style lang="scss">
.modal-xl {
  max-width: 95%;
}
</style>
