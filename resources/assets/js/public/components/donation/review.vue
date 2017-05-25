<template>
    <div id="step-4-wrapper" class="step">
        <div class="column">
            <img class="logo" src="/images/logo.png" alt="Take the City">
            <h4 class="has-text-centered title is-4">Let's review everything!</h4>
            <div v-if="donation.recurring">
                <p>You're gift will be a monthly recurring gift in the amount of <span class="strong">${{ donation.total / 100 }}</span>.
                </p>
            </div>
            <div v-else>
                <p>You're gift is a one-time gift in the amount of <span class="strong">${{ donation.total / 100 }}</span>.
                </p>
            </div>
            <p>Your name is <span class="strong">{{ fullName }}</span> and your live at <span class="strong">{{ address }}</span>.
                As a side note, we will use that address to mail your donation receipts.</p>
            <p>If everything above looks good, click the give button below and let's partner together to transform a
                city!</p>
            <button id="submitFormButton"
                    type="button"
                    :class="[isProcessing ? 'is-loading' : '']"
                    @click.prevent="submitDonation"
                    class="button is-primary is-large">
                <span><img src="/images/checkmark.svg" alt="Donate">Give!</span>
            </button>
            <div class="notification is-danger error-notification" v-if="this.donation.error">
                {{ this.donation.error }}
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        props: ['donation', 'isProcessing'],

        computed: {
            /**
             * We want the user's full name, so we use a computed property here to do so.
             *
             * @return {string}
             */
            fullName() {
                return _.capitalize(this.donation.firstName) + ' ' + _.capitalize(this.donation.lastName);
            },

            /**
             * We want to capitalize the first letter in each word of the address, cause we pay attention to
             * things like that :)
             *
             * @return {string}
             */
            address() {
                return this.donation.address.split(' ').map(word => {
                    return _.capitalize(word)
                }).join(' ');
            }
        },

        methods: {
            /**
             * Fire an event to submit the donation.
             */
            submitDonation() {
                this.$emit('submitDonation');
            }
        },
    }


</script>
<style lang="stylus" rel="stylesheet/sass">
    #submitFormButton
        width: 100%
        margin-top: 24px
        display: flex
        min-width: 100%
        display: -webkit-box
        -webkit-box-pack: center
        -webkit-box-align: center
        text-align: center

        span
            justify-content: center
            display: flex
            -webkit-box-pack: center
            -webkit-box-align: center

            img
                max-height: 26px
                max-width: 30px
                display: flex
                align-items: center

    .logo
        margin-bottom: 24px

    .error-notification
        width: 100%
        margin-top: 18px
        text-align: center
        background: lighten(#8f0900, 10%) !important
        font-weight: 400
        font-size: 16px

</style>
