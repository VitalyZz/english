<template>
  <td class="selectElements">
    <div class="wrapperCheckbox">
      <input v-model="checked" @change="onChange" type="checkbox" class="custom-checkbox" :id="word.id_word_information" :value="word.id_word_information">
      <label :for="word.id_word_information">{{ index + 1 }}</label>
    </div>
  </td>
  <td class="word">{{ word.word }}</td>
  <td class="translation">{{ word.translation }}</td>
  <td class="correct">{{ word.correct ?? 0 }}</td>
  <td class="wrong">{{ word.wrong ?? 0 }}</td>
  <td class="dictionary">{{ word.title }}</td>
</template>

<script>
export default {
  emits: ['update:modelValue'],
  props: ['word', 'index', 'modelValue'],
  data() {
    return {
      check: false,
    }
  },
  computed: {
    checked: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.check = value
      }
    }
  },
  methods: {
    onChange(e) {
      this.$emit('update:modelValue', this.check)
    }
  },
}
</script>

<style scoped lang="scss">
  td {
    padding: 10px;
    background-color: #465361;
    border-bottom: 2px solid #36404A;
  }

  .wrapperCheckbox {
    display: flex;
    justify-content: center;
  }

  td.selectElements {
    label {
      color: #fff;
    }
  }

  td.word, td.translation, td.correct, td.wrong, td.dictionary {
    font-size: 16px;
    font-weight: bold;
    color: #fff;
  }

  td.word, td.correct, td.wrong, td.dictionary {
    text-align: center;
  }

  .list {
    display: flex;
    justify-content: center;
    select {
      border-radius: 5px;
      background-color: #2bb1ff;
      padding: 5px 10px;
      font-size: 14px;
      color: #fff;
      font-weight: bold;
      border: none;
      outline: none;
    }
  }

  .custom-checkbox {
    position: absolute;
    z-index: -1;
    opacity: 0;

    &:checked + label:before {
      border-color: #0DFF92;
      background-color: #0DFF92;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e");
    }

    /* стили при наведении курсора на checkbox */
    &:not(:disabled):not(:checked)+label:hover::before {
      border-color: #87ffc5;
    }
    /* стили для активного состояния чекбокса (при нажатии на него) */
    &:not(:disabled):active+label::before {
      background-color: #87ffc5;
      border-color: #87ffc5;
    }
    /* стили для чекбокса, находящегося в фокусе */
    &:focus+label::before {
      box-shadow: 0 0 0 0.2rem rgba(0, 255, 85, 0.25);
    }
    /* стили для чекбокса, находящегося в фокусе и не находящегося в состоянии checked */
    &:focus:not(:checked)+label::before {
      border-color: #87ffc5;
    }
    /* стили для чекбокса, находящегося в состоянии disabled */
    &:disabled+label::before {
      background-color: #e9ecef;
    }

    & + label {
      display: inline-flex;
      align-items: center;
      user-select: none;

      &:before {
        content: '';
        display: inline-block;
        width: 1em;
        height: 1em;
        flex-shrink: 0;
        flex-grow: 0;
        border: 1px solid #adb5bd;
        border-radius: 0.25em;
        margin-right: 0.5em;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 50% 50%;
      }
    }
  }

  //.checkbox {
  //  margin-right: 10px;
  //  padding: 10px;
  //}

  .index {
    font-size: 18px;
    color: #fff;
  }
</style>