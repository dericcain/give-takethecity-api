<template>
    <div id="step-3-wrapper" class="step">
        <div class="column">
            <h1 class="has-text-centered is-1"><i class="fa fa-credit-card"></i></h1>
            <h4 class="has-text-centered title is-4">Credit Card Info</h4>
            <p class="has-text-centered">Please fill out all of the credit card information below.</p>
            <p class="control is-expanded">
                <input id="nameOnCard"
                       tabindex="-1"
                       class="input"
                       type="text"
                       v-validate
                       data-vv-delay="1000"
                       data-vv-scope="cc"
                       data-vv-rules="required"
                       data-vv-name="nameOnCard"
                       data-vv-as="Name on Card"
                       placeholder="Name on Card"
                       @keyup="updateCreditCard">
                <span v-show="errors.has('nameOnCard')" class="help is-danger">{{ errors.first('nameOnCard') }}</span>
            </p>
            <p class="control is-expanded">
                <input id="cc" class="input"
                       type="text"
                       v-validate
                       data-vv-delay="1000"
                       data-vv-scope="cc"
                       data-vv-rules="required|credit_card"
                       data-vv-name="cc"
                       data-vv-as="Credit Card Number"
                       data-stripe="number"
                       placeholder="Credit Card Number"
                       @keyup="updateCreditCard">
                <span v-show="errors.has('cc')" class="help is-danger">{{ errors.first('cc') }}</span>
            </p>
            <div class="control is-grouped">
                <p class="control is-expanded">
                    <input id="cvc"
                           class="input"
                           type="text"
                           v-validate
                           data-vv-delay="1000"
                           data-vv-scope="cc"
                           data-vv-rules="required|numeric"
                           data-vv-name="cvc"
                           data-vv-as="Security Code"
                           data-stripe="cvc"
                           placeholder="CVC"
                           @keyup="updateCreditCard">
                    <span v-show="errors.has('cvc')" class="help is-danger">{{ errors.first('cvc') }}</span>
                </p>
                <p class="control exp">
                    <span class="select">
                        <select id="expMonth"
                                v-validate
                                data-vv-scope="cc"
                                data-vv-rules="required"
                                data-vv-name="exp_month"
                                data-vv-as="Expiration Month"
                                data-stripe="exp_month"
                                @change="updateCreditCard">
                            <option value=''>Exp Month</option>
                            <option value='1'>Janaury</option>
                            <option value='2'>February</option>
                            <option value='3'>March</option>
                            <option value='4'>April</option>
                            <option value='5'>May</option>
                            <option value='6'>June</option>
                            <option value='7'>July</option>
                            <option value='8'>August</option>
                            <option value='9'>September</option>
                            <option value='10'>October</option>
                            <option value='11'>November</option>
                            <option value='12'>December</option>
                        </select>
                    </span>
                    <span v-show="errors.has('exp_month')" class="help is-danger">{{ errors.first('exp_month') }}</span>
                </p>
                <p class="control exp">
                    <span class="select">
                        <select id="expYear"
                                v-validate
                                data-vv-scope="cc"
                                data-vv-rules="required"
                                data-vv-name="exp_year"
                                data-vv-as="Expiration Year"
                                data-stripe="exp_year"
                                @change="updateCreditCard">
                            <option value=''>Exp Year</option>
                            <option v-for="year in years">{{ year }}</option>
                        </select>
                    </span>
                    <span v-show="errors.has('exp_year')" class="help is-danger">{{ errors.first('exp_year') }}</span>
                </p>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        props: ['donation'],
        data() {
            return {
                years: []
            }
        },

        methods: {
            /**
             * We will use this to create our year drop down from an array of years.
             */
            updateYearDropdown() {
                let min = new Date().getFullYear(),
                    max = min + 9;
                for (let year = min; year < max; year++) {
                    this.years.push(year);
                }
            },

            /**
             * Fire an event to update the credit card info part of the donation.
             *
             * @param event
             */
            updateCreditCard(event) {
                this.$emit('update', event.target.id, event.target.value);
                this.validateCreditCardInfo();
            },

            /**
             * Validate the credit card info section of the form.
             *
             * @return {Promise<R2|R1>|*|Promise.<TResult>|Promise<R>}
             */
            validateCreditCardInfo() {
                return this.$validator.validateAll('cc').then(response => {
                    if (response) this.$emit('toggleValid', true);
                }).catch(error => false);
            }
        },

        created() {
            this.updateYearDropdown();
        }
    }


</script>

<style lang="stylus" rel="stylesheet/sass">
    .fa-credit-card
        color: #8f0900
        font-size: 40px
        margin-bottom: 18px

</style>
