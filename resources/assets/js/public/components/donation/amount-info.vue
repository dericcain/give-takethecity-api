<template>
    <div id="step-1-wrapper" class="step">
        <div class="top column">
            <div class="column">
                <img src="/images/logo.png" alt="Take the City" class="logo">
            </div>
            <h2 class="has-text-centered title is-2">Thanks for giving!</h2>
            <p>We are honored the you want to partner with us to transform cities! Your gift will have a direct impact
                on the lives of those who need to hear the hope of Christ. If you want to learn more about what we do,
                <a href="https://takethecity.com">click here</a>.</p>
        </div>
        <div class="bottom column">
            <div id="amount">
                <label class="label">How much would you like to give?</label>
                <div class="columns is-multiline is-gapless is-mobile">
                    <div class="column is-half">
                        <button type="button"
                                class="button give-button"
                                :class="[donation.amount == 2500 ? 'is-active' : '']"
                                @click="setAmount(2500)">$25
                        </button>
                    </div>
                    <div class="column is-half">
                        <button type="button"
                                class="button give-button"
                                :class="[donation.amount == 5000 ? 'is-active' : '']"
                                @click="setAmount(5000)">$50
                        </button>
                    </div>
                    <div class="column is-half">
                        <button type="button"
                                class="button give-button"
                                :class="[donation.amount == 10000 ? 'is-active' : '']"
                                @click="setAmount(10000)">$100
                        </button>
                    </div>
                    <div class="column is-half">
                        <button type="button"
                                class="button give-button"
                                :class="[donation.amount == 25000 ? 'is-active' : '']"
                                @click="setAmount(25000)">$250
                        </button>
                    </div>
                    <div class="column is-12">
                        <p class="control amount has-icon">
                            <input class="input"
                                   id="other-amount"
                                   v-validate
                                   data-vv-rules="required|decimal:2"
                                   data-vv-name="amount"
                                   type="number"
                                   @keyup="setAmount"
                                   placeholder="Other amount?">
                            <span class="icon is-small">
                                <i class="fa fa-usd"></i>
                            </span>
                        </p>
                    </div>
                    <div id="cover-fees" class="column is-12" v-if="donation.amount != '0' || donation.amount != 0">
                        <p>I'd like to cover the ${{ donation.fees / 100 }} processing fee so 100% of my donation goes to Take the City.</p>
                        <button type="button"
                                class="button"
                                :class="[donation.willCoverFees == true ? 'is-success' : '']"
                                @click="willCoverFees">
                            <i class="fa fa-check" :class="[donation.willCoverFees == true ? '' : 'is-hidden']"></i>
                            <span v-if="!donation.willCoverFees">Click to cover fees!</span>
                            <span v-if="donation.willCoverFees">Fees covered!</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['donation', 'isValid'],

        methods: {

            /**
             * Make the step active if it is the button that was clicked.
             *
             * @param amount
             * @return {boolean}
             */
            isActive(amount) {
                return amount == this.donation.amount;
            },

            /**
             * We need to figure out if we are dealing with a button being clicked (number) or filling in the
             * input (string) and updated the donation amount with that.
             *
             * @param amount
             */
            setAmount(amount) {
                if (isNaN(amount)) {
                    amount = amount.target.value * 100;
                } else {
                    document.getElementById('other-amount').value = '';
                }
                this.$emit('update', 'amount', amount);
                this.$emit('toggleValid', this.$validator.validate('amount', this.$parent.donation.amount));
            },

            /**
             * We need to update the will cover fees field.
             */
            willCoverFees() {
                this.$emit('willCoverFees');
            }
        }
    }

</script>
<style lang="stylus" rel="stylesheet/sass">
    h2
        margin-bottom: 6px !important
    #amount
        width: 90%

        .column
            padding: 3px !important

        .amount
            width: 100%

            .fa
                font-size: 18px
                margin-top: 6px

            input
                font-size: 18px
                text-align: center
                box-shadow: none
                padding-left: .75em !important

        .give-button
            display: flex
            flex: 1
            min-width: 100%
            display: -webkit-box
            -webkit-box-pack: center
            -webkit-box-align: center

    .logo
        margin: 0 auto !important

    #cover-fees.column
        margin-bottom: 6px !important
        background: #FAFAFA
        box-shadow: 0 1px 1px rgba(0,0,0,.15), 0 1px 2px rgba(0,0,0,.2)
        padding: 12px !important
        p
            font-weight: 600 !important
</style>
