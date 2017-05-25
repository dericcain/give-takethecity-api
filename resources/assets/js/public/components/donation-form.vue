<template>
    <form action="/" id="give" @submit.prevent="submitDonation">
        <div id="inner-form-wrapper" :class="sliderClass">

            <amount-info @update="updateDonationInformation"
                         @toggleValid="toggleValid"
                         @willCoverFees="willCoverFees"
                         :isValid="isValid"
                         :donation="donation">
            </amount-info>

            <personal-info @update="updateDonationInformation"
                           @updateRecurring="updateRecurring"
                           @toggleValid="toggleValid"
                           :isValid="isValid"
                           :designations="designations"
                           :donation="donation">
            </personal-info>

            <credit-card-info @toggleValid="toggleValid"
                              :isValid="isValid"
                              @update="updateDonationInformation"
                              :donation="donation">
            </credit-card-info>

            <review :isProcessing="isProcessing"
                    @submitDonation="submitDonation"
                    :donation="donation">
            </review>

            <confirmation :donation="donation"></confirmation>

        </div>

        <nav-buttons v-show="!isComplete"
                     :isMobile="isMobile"
                     :width="width"
                     :isComplete="isComplete"
                     :innerFormWrapper="innerFormWrapper"
                     :isValid="isValid"
                     @toggleValid="toggleValid">
        </nav-buttons>
    </form>
</template>

<script type="text/babel">
    import axios from 'axios';
    import navButtons from './donation/nav-buttons.vue';
    import amountInfo from './donation/amount-info.vue';
    import personalInfo from './donation/peronsal-info.vue';
    import creditCardInfo from './donation/credit-card-info.vue';
    import review from './donation/review.vue';
    import confirmation from './donation/confirmation.vue';

    export default {
        data() {
            return {
                currentStep: 1,
                classList: {
                    1: 'one',
                    2: 'two',
                    3: 'three',
                    4: 'four',
                    5: 'five'
                },
                sliderClass: 'one',
                isValid: false,
                isProcessing: false,
                designations: [],
                donation: {
                    token: '',
                    amount: 0,
                    recurring: false,
                    designation: 'Where needed most',
                    firstName: '',
                    lastName: '',
                    address: '',
                    zip: '',
                    phone: '',
                    email: '',
                    cc: '',
                    cvc: '',
                    exp_month: '',
                    exp_year: '',
                    nameOnCard: '',
                    missionSupport: '',
                    staffSupport: '',
                    willCoverFees: false,
                    fees: 0,
                    total: 0,
                    error: ''
                },
                width: 0,
                steps: '',
                innerFormWrapper: '',
                position: 0,
                isMobile: false,
                isComplete: false
            }
        },

        components: {
            'nav-buttons': navButtons,
            'amount-info': amountInfo,
            'personal-info': personalInfo,
            'credit-card-info': creditCardInfo,
            'review': review,
            'confirmation': confirmation
        },

        methods: {
            /**
             * Update the step class so we can change what step we are on.
             *
             * @param stepNumber
             * @return {*}
             */
            changeStep(stepNumber) {
                return this.sliderClass = this.classList[stepNumber];
            },

            /**
             * Update the donation information depending on what field is updated.
             *
             * @param field
             * @param value
             */
            updateDonationInformation(field, value) {
                this.donation[field] = value;
                this.donation.fees = this.calculateFees();
                this.calculateTotal();
            },

            /**
             * Toggle will cover fees field.
             */
            willCoverFees() {
                this.donation.willCoverFees = !this.donation.willCoverFees;
                this.calculateTotal();
            },

            /**
             * We need a total because we need to add fees to the amount if the user is going to pay them.
             */
            calculateTotal() {
                if(this.donation.willCoverFees) {
                    this.donation.total = this.donation.amount + this.donation.fees;
                } else {
                    this.donation.total = this.donation.amount;
                }
            },

            /**
             * Toggle the donation as recurring.
             */
            updateRecurring() {
                this.donation.recurring = !this.donation.recurring;
            },

            /**
             * This means that the form fields on the current step are valid.
             *
             * @param state
             */
            toggleValid(state) {
                this.isValid = state;
            },

            /**
             * This will handle the donation submission to the payment processor.
             */
            submitDonation() {
                this.isProcessing = true;
                let form = document.getElementById('give');
                Stripe.card.createToken(form, this.sendDataToServer);
                return false;
            },

            /**
             * Send the data to the server.
             */
            sendDataToServer(status, response) {
                if (response.error) {
                    return false;
                } else {
                    this.donation.token = response.id;
                    delete this.donation.cc;
                    delete this.donation.cvc;
                    axios.post('/', this.donation)
                        .then(response => {
                            console.log('Response: ' + response);
                            if (response.data.status == "succeeded") {
                                this.changeStep(5);
                            }
                        })
                        .catch(error => {
                            this.isProcessing = false;
                            this.donation.error = error.response.data.error;
                        });
                }
            },

            /**
             * We need this for mobile devices. We will resize each step to be the full width of the screen. We
             * are also using a debounce here so we don't fire the event too many times within a given time.
             */
            resizeEachStep() {
                this.steps = document.getElementsByClassName('step');
                _.each(this.steps, step => {
                    step.style.width = this.width + 'px';
                });
            },

            /**
             * On mobile, we will make the full slider the length of how many steps we have.
             */
            resizeSliderWrapper() {
                this.innerFormWrapper = document.getElementById('inner-form-wrapper');
                this.innerFormWrapper.style.width = (this.steps.length * this.width) + 'px';
            },

            /**
             * Checking to see if we are on a mobile device or not.
             */
            checkMobile() {
                this.width = window.innerWidth;
                this.isMobile = this.width < 601;
            },

            /**
             * These events will be fired if we are on a mobile device.
             */
            fireMobileEvents() {
                this.checkMobile();
                if (this.isMobile) {
                    this.resizeEachStep();
                    this.resizeSliderWrapper();
                }
            },

            /**
             * Attach an event listener to the window resize event.
             */
            watchForResizeEvent() {
                window.addEventListener('resize', _.debounce(this.fireMobileEvents, 400));
            },

            /**
             * We need to calculate the fees that stripe is charging us.
             */
            calculateFees() {
                return parseInt((((this.donation.amount + 30) / (1 - .026)) - (this.donation.amount)));
            },

            getDesignations() {
                axios.get('/designations')
                    .then(response => this.designations = response.data)
                    .catch(error => console.log(error));
            }
        },

        /**
         * This means that everything is loaded and ready to go with Vue
         */
        mounted() {
            this.checkMobile();
            this.fireMobileEvents();
            this.watchForResizeEvent();
            this.getDesignations();
        },

        watch: {
            /**
             * When the current step changes, we need to update the slider class so our page changes.
             */
            currentStep() {
                this.sliderClass = this.classList[this.currentStep];
            },
        }
    }

</script>

<style lang="stylus" rel="stylesheet/sass">
    #give
        width: inherit

</style>