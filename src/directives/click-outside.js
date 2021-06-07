export default {
    mounted(el, binding) {
        el.addEventListener('click', e => e.stopPropagation());
        document.body.addEventListener('click', binding.value);
    },
    beforeUnmount(el, binding) {
        document.body.removeEventListener('click', binding.value);
    }
}