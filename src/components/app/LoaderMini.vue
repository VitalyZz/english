<template>
  <div class="lds-ripple">
    <div></div>
    <div></div>
  </div>
</template>

<script>
export default {
}
</script>

<style scoped lang="scss">
.lds-ripple {
  display: inline-block;
  position: relative;
  width: 20px;
  height: 20px;

  div {
    position: absolute;
    border: 1px solid #cef;
    opacity: 1;
    border-radius: 50%;
    animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;

    &:nth-child(2) {
      animation-delay: -0.5s;
    }
  }
}

@keyframes lds-ripple {
  0% {
    top: 10px;
    left: 10px;
    width: 0;
    height: 0;
    opacity: 1;
  }
  100% {
    top: 0;
    left: 0;
    width: 20px;
    height: 20px;
    opacity: 0;
  }
}
</style>