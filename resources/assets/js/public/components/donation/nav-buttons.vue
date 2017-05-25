<template>
    <div class="bottom column">
        <div class="buttons">
            <button id="prev-button"
                    type="button"
                    class="button is-large is-light"
                    :class="[step == 1 ? 'is-disabled' : '']"
                    @click="goBack">
                Previous
            </button>
            <button id="next-button"
                    type="button"
                    class="button is-large is-dark"
                    :class="[isValid ? '' : 'is-disabled']"
                    @click="goNext">
                Next
            </button>
        </div>
    </div>
</template>
<script>

    export default {
        props: ['isValid', 'isMobile', 'width', 'innerFormWrapper', 'isComplete'],

        data() {
            return {
                step: 1,
                wrapper: ''
            }
        },

        methods: {
            /**
             * Go back a step. We need to make sure the we are not on step 1 because we can't go back any further from
             * there.
             *
             * @return {*}
             */
            goBack() {
                if (this.step <= 1) {
                    return false;
                }
                this.moveBackward();
                this.$emit('toggleValid', true);

                this.step--;
            },

            /**
             * Go to the next step.
             *
             * @return {number}
             */
            goNext() {
                this.moveForward();
                this.$parent.isValid = false;
                this.step++;
            },

            /**
             * This is the main method for changing steps.
             *
             * @param backward
             */
            move(backward = false) {
                if (this.isMobile) {
                    let style = window.getComputedStyle(this.innerFormWrapper),
                        position = style.getPropertyValue('left');
                    if (backward) {
                        this.innerFormWrapper.style.left = (this.parseCurrentPosition(position) + this.width) + 'px';
                    } else {
                        this.innerFormWrapper.style.left = (this.parseCurrentPosition(position) - this.width) + 'px';
                    }
                }
            },

            /**
             * We need our position to end up as a string with 'px' on the end of it.
             *
             * @param number
             * @return {Number}
             */
            parseCurrentPosition(number) {
                return parseInt(_.trimEnd(number, 'px'));
            },

            /**
             * Simple wrapper over the move method to move forward.
             */
            moveForward() {
                this.move();
            },

            /**
             * Wrapper on the move method to move backwards.
             */
            moveBackward() {
                this.move(true);
            }
        },

        watch: {
            /**
             * If the step changes, we need to update the parent.
             *
             * @return {watch.step}
             */
            step() {
                return this.$parent.currentStep = this.step;
            },

            /**
             * When the donation is complete, let's move forward.
             */
            isComplete() {
                console.log('complete');
                this.moveForward();
            }
        }

    }


</script>
<style lang="stylus" rel="stylesheet/sass">
    .buttons
        position: absolute
        bottom: 0
        width: 100%
        left: 0
        display: flex

        .button
            flex: 1
            border-radius: 0
            display: -webkit-box
            -webkit-box-pack: center
            -webkit-box-align: center

</style>
