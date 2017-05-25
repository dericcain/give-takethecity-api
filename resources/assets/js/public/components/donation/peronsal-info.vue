<template>
    <div id="step-2-wrapper" class="step">
        <div class="column">
            <h1 class="has-text-centered is-1"><i class="fa fa-envelope"></i></h1>
            <h4 class="has-text-centered title is-4">Personal Info</h4>
            <label class="label">How would you like to designate this gift?</label>
            <p class="control">
                <span class="select">
                    <select id="designation"
                            @change="updatePersonalInfo"
                            title="Designation">
                        <option v-for="item in designations" v-bind:value="item.id">{{ item.name }}</option>
                    </select>
                </span>
            </p>
            <div class="control is-grouped">
                <p class="control is-expanded">
                    <input id="firstName"
                           class="input"
                           type="text"
                           v-validate
                           data-vv-delay="1000"
                           data-vv-scope="personal-info"
                           data-vv-rules="required"
                           data-vv-name="firstName"
                           data-vv-as="First Name"
                           placeholder="First Name"
                           @keyup="updatePersonalInfo">
                    <span v-show="errors.has('firstName')" class="help is-danger">{{ errors.first('firstName') }}</span>
                </p>
                <p class="control is-expanded">
                    <input id="lastName"
                           class="input"
                           type="text"
                           v-validate
                           data-vv-delay="1000"
                           data-vv-scope="personal-info"
                           data-vv-rules="required"
                           data-vv-name="lastName"
                           data-vv-as="Last Name"
                           placeholder="Last Name"
                           @keyup="updatePersonalInfo">
                    <span v-show="errors.has('lastName')" class="help is-danger">{{ errors.first('lastName') }}</span>
                </p>
            </div>
            <p class="control is-expanded">
                <input id="email"
                       class="input"
                       type="email"
                       v-validate
                       data-vv-delay="1000"
                       data-vv-scope="personal-info"
                       data-vv-rules="required|email"
                       data-vv-name="email"
                       data-vv-as="Email"
                       placeholder="Email"
                       @keyup="updatePersonalInfo">
                <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
            </p>
            <p class="control is-expanded">
                <input id="address"
                       class="input"
                       type="text"
                       v-validate
                       data-vv-delay="1000"
                       data-vv-scope="personal-info"
                       data-vv-rules="required"
                       data-vv-name="address"
                       data-vv-as="Address"
                       placeholder="Address"
                       @keyup="updatePersonalInfo">
                <span v-show="errors.has('address')" class="help is-danger">{{ errors.first('address') }}</span>
            </p>
            <div class="control is-grouped">
                <p class="control is-expanded">
                    <input id="zip"
                           class="input"
                           type="text"
                           v-validate
                           data-vv-delay="1000"
                           data-vv-scope="personal-info"
                           data-vv-rules="required|numeric|min:5"
                           data-vv-name="zip"
                           data-vv-as="Zip"
                           data-stripe="address_zip"
                           placeholder="Zip"
                           @keyup="updatePersonalInfo">
                    <span v-show="errors.has('zip')" class="help is-danger">{{ errors.first('zip') }}</span>
                </p>
                <p class="control is-expanded">
                    <input id="phone"
                           v-mask="'(###)###-####'"
                           class="input"
                           type="text"
                           placeholder="Phone"
                           @keyup="updatePersonalInfo">
                </p>
            </div>
            <p class="control" v-if="donation.designation == '4'">
                <textarea id="staffSupport"
                          class="textarea"
                          @keyup="updatePersonalInfo"
                          v-validate
                          data-vv-delay="1000"
                          data-vv-scope="personal-info"
                          data-vv-as="Staff Support"
                          data-vv-rules="required"
                          placeholder="What staff member would you like to support?"></textarea>
                <span v-show="errors.has('staffSupport')"
                      class="help is-danger">{{ errors.first('staffSupport') }}</span>
            </p>
            <p class="control" v-if="donation.designation == '5'">
                <textarea id="missionSupport"
                          rows="2"
                          class="textarea"
                          @keyup="updatePersonalInfo"
                          v-validate
                          data-vv-delay="1000"
                          data-vv-scope="personal-info"
                          data-vv-as="Mission Trip Support"
                          data-vv-rules="required"
                          placeholder="Who would you like to support? Also, what trip are they going on?"></textarea>
                <span v-show="errors.has('missionSupport')"
                      class="help is-danger">{{ errors.first('missionSupport') }}</span>
            </p>
            <button class="button is-light recurring-button"
                    type="button"
                    :class="[donation.recurring ? 'active' : '']"
                    @click="toggleRecurring">
                <span v-if="donation.recurring === false"><i class="fa fa-heart"></i> Make this gift recurring?</span>
                <span v-if="donation.recurring"><i class="fa fa-heart"></i> This gift will be recurring!</span>
            </button>
        </div>
    </div>
</template>
<script>

    export default {
        props: ['donation', 'isValid', 'designations'],
        data() {
            return {
//                designation: [
//                    'Where needed most',
//                    'TheDoor',
//                    'Eminent Worship',
//                    'Staff Support',
//                    'Mission Trip Support',
//                    'REDEEM'
//                ]
            }
        },

        methods: {

            /**
             * Toggle the donation and make it recurring or not.
             */
            toggleRecurring() {
                this.$emit('updateRecurring')
            },

            /**
             * Validate the form fields.
             *
             * @return {Promise<R2|R1>|*|Promise.<TResult>|Promise<R>}
             */
            validateFields() {
                return this.$validator.validateAll('personal-info').then((response) => {
                    if (response) this.$emit('toggleValid', true);
                }).catch((error) => false);
            },

            /**
             * Fire an event to update the personal info part of the donation.
             *
             * @param event
             */
            updatePersonalInfo(event) {
                this.$emit('update', event.target.id, event.target.value);
                this.validateFields();
            }
        }
    }


</script>
<style lang="stylus" rel="stylesheet/sass">

    .recurring-button
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

            i
                font-size: 14px
                margin-right: 6px
                color: #8f0900
                display: flex
                align-items: center

        &.active
            background: #8f0900
            color: #FFF !important

            &:hover
                background-color: lighten(#8f0900, 5%)
            i
                color: #FFF
    .fa-envelope
        color: #8f0900
        font-size: 40px
        margin-bottom: 18px

    textarea
        min-height: 60px !important
        max-height: 120px !important



</style>
