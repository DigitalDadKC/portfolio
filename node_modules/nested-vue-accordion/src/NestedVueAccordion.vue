<template>
  <div
    class="accordion accordion-flush"
    :class="[item.children ? 'have-child' : 'no-child']"
    v-for="(item, index) in items"
    :key="index"
  >
    <button
      v-if="item.title && item.children"
      type="button"
      class="accordion-button"
      :class="{ collapsed: !activeIndexes.includes(index) }"
      data-bs-toggle="collapse"
      :data-bs-target="`#collapse${index + 1}`"
      :aria-expanded="activeIndexes.includes(index)"
      :aria-controls="`collapse${index + 1}`"
      @click="toggleContent(index)"
    >
      {{ item.title }}
    </button>
    <div class="content-style" v-else>{{ item.title }}</div>
    <div
      class="accordion-collapse accordion-content"
      :class="[
        { collapse: !activeIndexes.includes(index) },
        item.children ? 'have-child' : 'no-child',
      ]"
      :id="`collapse${index + 1}`"
      v-if="activeIndexes.includes(index)"
    >
      <template v-if="item.children">
        <nested-vue-accordion :items="item.children" />
      </template>
      <div class="content-style" v-else>{{ item.title }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MultilevelAccordion",
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      activeIndexes: [],
      accordionId: "",
    };
  },
  methods: {
    toggleContent(index) {
      const currentIndex = this.activeIndexes.indexOf(index);
      if (currentIndex !== -1) {
        this.activeIndexes.splice(currentIndex, 1);
      } else {
        this.activeIndexes.push(index);
      }
    },
    dynamicIdGenerate() {
      let id = "id" + Math.random().toString(16).slice(2);
      this.accordionId = id;
      return this.accordionId;
    },
  },
};
</script>
<style scoped>
/* default css for accordion start here */
.accordion {
  --bs-accordion-color: var(--bs-body-color);
  --bs-accordion-bg: var(--bs-body-bg);
  --bs-accordion-transition: color 0.15s ease-in-out,
    background-color 0.15s ease-in-out, border-color 0.15s ease-in-out,
    box-shadow 0.15s ease-in-out, border-radius 0.15s ease;
  --bs-accordion-border-color: var(--bs-border-color);
  --bs-accordion-border-width: var(--bs-border-width);
  --bs-accordion-border-radius: var(--bs-border-radius);
  --bs-accordion-inner-border-radius: calc(
    var(--bs-border-radius) - (var(--bs-border-width))
  );
  --bs-accordion-btn-padding-x: 1.25rem;
  --bs-accordion-btn-padding-y: 1rem;
  --bs-accordion-btn-color: var(--bs-body-color);
  --bs-accordion-btn-bg: var(--bs-accordion-bg);
  --bs-accordion-btn-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23212529' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M2 5L8 11L14 5'/%3e%3c/svg%3e");
  --bs-accordion-btn-icon-width: 1.25rem;
  --bs-accordion-btn-icon-transform: rotate(-180deg);
  --bs-accordion-btn-icon-transition: transform 0.2s ease-in-out;
  --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23052c65' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M2 5L8 11L14 5'/%3e%3c/svg%3e");
  --bs-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  --bs-accordion-body-padding-x: 1.25rem;
  --bs-accordion-body-padding-y: 1rem;
  --bs-accordion-active-color: var(--bs-primary-text-emphasis);
  --bs-accordion-active-bg: var(--bs-primary-bg-subtle);
}

.accordion-button {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  padding: var(--bs-accordion-btn-padding-y) var(--bs-accordion-btn-padding-x);
  font-size: 1rem;
  color: var(--bs-accordion-btn-color);
  text-align: left;
  background-color: var(--bs-accordion-btn-bg);
  border: 0;
  border-radius: 0;
  overflow-anchor: none;
  transition: var(--bs-accordion-transition);
}
@media (prefers-reduced-motion: reduce) {
  .accordion-button {
    transition: none;
  }
}
.accordion-button:not(.collapsed) {
  color: var(--bs-accordion-active-color);
  background-color: var(--bs-accordion-active-bg);
  box-shadow: inset 0 calc(-1 * var(--bs-accordion-border-width)) 0
    var(--bs-accordion-border-color);
}
.accordion-button:not(.collapsed)::after {
  background-image: var(--bs-accordion-btn-active-icon);
  transform: var(--bs-accordion-btn-icon-transform);
}
.accordion-button::after {
  flex-shrink: 0;
  width: var(--bs-accordion-btn-icon-width);
  height: var(--bs-accordion-btn-icon-width);
  margin-left: auto;
  content: "";
  background-image: var(--bs-accordion-btn-icon);
  background-repeat: no-repeat;
  background-size: var(--bs-accordion-btn-icon-width);
  transition: var(--bs-accordion-btn-icon-transition);
}
@media (prefers-reduced-motion: reduce) {
  .accordion-button::after {
    transition: none;
  }
}
.accordion-button:hover {
  z-index: 2;
}
.accordion-button:focus {
  z-index: 3;
  outline: 0;
  box-shadow: var(--bs-accordion-btn-focus-box-shadow);
}

.accordion-header {
  margin-bottom: 0;
}

.accordion-item {
  color: var(--bs-accordion-color);
  background-color: var(--bs-accordion-bg);
  border: var(--bs-accordion-border-width) solid
    var(--bs-accordion-border-color);
}
.accordion-item:first-of-type {
  border-top-left-radius: var(--bs-accordion-border-radius);
  border-top-right-radius: var(--bs-accordion-border-radius);
}
.accordion-item:first-of-type > .accordion-header .accordion-button {
  border-top-left-radius: var(--bs-accordion-inner-border-radius);
  border-top-right-radius: var(--bs-accordion-inner-border-radius);
}
.accordion-item:not(:first-of-type) {
  border-top: 0;
}
.accordion-item:last-of-type {
  border-bottom-right-radius: var(--bs-accordion-border-radius);
  border-bottom-left-radius: var(--bs-accordion-border-radius);
}
.accordion-item:last-of-type > .accordion-header .accordion-button.collapsed {
  border-bottom-right-radius: var(--bs-accordion-inner-border-radius);
  border-bottom-left-radius: var(--bs-accordion-inner-border-radius);
}
.accordion-item:last-of-type > .accordion-collapse {
  border-bottom-right-radius: var(--bs-accordion-border-radius);
  border-bottom-left-radius: var(--bs-accordion-border-radius);
}

.accordion-body {
  padding: var(--bs-accordion-body-padding-y) var(--bs-accordion-body-padding-x);
}

.accordion-flush > .accordion-item {
  border-right: 0;
  border-left: 0;
  border-radius: 0;
}
.accordion-flush > .accordion-item:first-child {
  border-top: 0;
}
.accordion-flush > .accordion-item:last-child {
  border-bottom: 0;
}
.accordion-flush > .accordion-item > .accordion-header .accordion-button,
.accordion-flush
  > .accordion-item
  > .accordion-header
  .accordion-button.collapsed {
  border-radius: 0;
}
.accordion-flush > .accordion-item > .accordion-collapse {
  border-radius: 0;
}

[data-bs-theme="dark"] .accordion-button::after {
  --bs-accordion-btn-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236ea8fe'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
  --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236ea8fe'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}
/* default css for accordion end here */

.accordion {
  /* display: flex;
    flex-direction: column; */
  margin: 0;
  padding: 0;
}

.accordion-button {
  border: none;
  cursor: pointer;
  padding: 16px 18px;
  text-align: left;
  width: 100%;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  transition: 0.3s;
}
.accordion-button[aria-expanded="true"] {
  background-color: #f3f3f3;
}
.accordion-button:hover {
  opacity: 0.8;
}
.accordion-button:focus {
  box-shadow: none;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
}
.accordion-content {
  padding: 0 0 0 18px;
  transition: max-height 0.3s ease-in-out;
}
.content-style {
  padding: 16px 18px;
  border: 1px solid #ddd;
  margin-top: 15px;
  margin-bottom: 15px;
  display: flex;
  max-width: 200px;
  max-height: 200px;
}
.have-child .no-child {
  display: inline-block;
  margin-right: 15px;
}
</style>
nested-vue-accordiona/src/MultilevelAccordion.vue
