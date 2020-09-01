<template>
    <svg class="svg-icon" :style="styles" aria-hidden="true">
        <use :xlink:href="iconName" />
    </svg>
</template>

<script>
export default {
    name: "icon-svg",
    props: {
        iconClass: {
            type: String,
            required: true
        },
        size: {
            type: Number,
            default: 18
        },
        color: {
            type: String,
            default: "currentColor"
        },
        otherStyle: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            styles: {
                "font-size": this.size + "px",
                fill: this.color,
                ...this.inputStyle
            }
        };
    },
    computed: {
        iconName() {
            return `#${this.iconClass}`;
        }
    }
};
</script>

<style>
.svg-icon {
    width: 1em;
    height: 1em;
    fill: currentColor;
    overflow: hidden;
}
</style>
