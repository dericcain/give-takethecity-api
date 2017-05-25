<template>
    <div id="confirmation" class="step">
        <div class="column">
            <img class="logo" src="/images/logo.png" alt="Take the City">
            <h4 class="has-text-centered title is-4">Thank you, {{ name }}.</h4>
            <p>We can't thank you enough for partnering with us to help in transforming cities. Please continue to pray
                for us as we work diligently to reach our goal of Taking the City.</p>
            <h5 class="has-text-centered title is-5">Your gift was received!</h5>
            <img src="/images/ok.svg" alt="Take the City" class="completed">
            <a href="http://takethecity.com" class="button visit-site">Visit our site!</a>
        </div>
    </div>
</template>
<script>

    export default {
        props: ['donation'],

        computed: {
            name() {
                return _.capitalize(this.donation.firstName);
            }
        },
    }


</script>
<style lang="stylus" rel="stylesheet/sass">
    img.completed
        max-height: 120px

    .is-5
        font-weight: 600

    .visit-site
        margin-top: 24px
</style>
