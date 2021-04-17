<script src="/admin-assets/libs/select2/select2.js"></script>
<script src="https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAPS_API')}}"></script>
<script src="/admin-assets/libs/gmaps/gmaps.js"></script>
{{--    <script src="/js/charts_gmaps.js"></script>--}}

<script>
    $(function () {
        $('.select2-categories').select2({
            placeholder: 'Select food categories',
        });
        $('.select2-search-tags').select2({
            placeholder: 'Select search tags',
        });
        const lat = {!! json_encode(isset($branch->latitude) ? $branch->latitude: config('defaults.geolocation.latitude')) !!};
        const lng = {!! json_encode(isset($branch->longitude)? $branch->longitude : config('defaults.geolocation.longitude')) !!};
        latitude.value = lat;
        longitude.value = lng;
        const map = new GMaps({el: '#gmaps-branch', lat: lat, lng: lng});
        const marker = map.addMarker({
            lat: lat,
            lng: lng,
            streetViewControl: false,
            draggable: true,
        });
        marker.addListener('dragend', function () {
            latitude.value = marker.getPosition().lat();
            longitude.value = marker.getPosition().lng();
        });
    });

    new Vue({
        el: '#vue-app',
        data: {
            branch: @json($branch),
            regions: @json($regions),
            foodCategories: @json($foodCategories),
            cities: [],
            chains: @json($chains),
            contactDetails: @json($contacts),
            daysTranslation: window.App.translations,
            workingHours: @json($workingHours),
            contactDetail: {
                name: '',
                position: '',
                email: '',
                phone: ''
            },
            validationData: [],
            formErrorMessage: null,
            selectedRegion: null,
            isTipTopDelivery: true,
            isRestaurantDelivery: true,
        },
        watch: {
            branch: {
                handler: function (val) {
                    if (!this.selectedRegion || this.selectedRegion.id != val.region.id) {
                        this.selectedRegion = val.region;
                        if (this.branch.city != null) {
                            this.branch.city = null
                        }
                    }
                },
                deep: true,
            }
        },
        methods: {
            retrieveCities: function (region) {
                axios.post(window.App.domain + `/ajax/countries/${region.country_id}/regions/${region.id}/cities`)
                    .then((res) => {
                        this.cities = res.data;
                    });
            },
            addNewContact: function () {
                this.contactDetails.push(JSON.parse(JSON.stringify(this.contactDetail)))

            },
            removeItem: function (id) {
                this.contactDetails.splice(this.contactDetails.indexOf(id), 1);

            },
            submitButton(e) {
                if (this.contactDetails.length) {
                    let validationData = this.validationData;
                    let name = false
                    let phone = false
                    const titleElement = this.$refs['main-form'].elements.namedItem('en[title]');
                    console.log("titleElement");
                    console.log(titleElement);
                    this.contactDetails.forEach(function (element) {
                        name = element.name.length
                        phone = element.phone.length
                    })
                    validationData[0] = {'Name': name};
                    validationData[1] = {'Phone': phone};
                    for (let i = 0; i < validationData.length; i++) {
                        const tmpItem = validationData[i], inputLabel = Object.keys(tmpItem)[0];
                        if (!tmpItem[inputLabel]) {
                            this.setErrorMessage(`${inputLabel} is required.`);
                            break;
                        }
                    }
                    if (!!this.formErrorMessage) {
                        e.preventDefault();
                    } else {
                        this.$refs['main-form'].submit();
                    }
                } else {
                    this.$refs['main-form'].submit();
                }
            },
            setErrorMessage(msg) {
                this.formErrorMessage = msg;
                setTimeout(_ => {
                    this.formErrorMessage = null;
                }, 2500);
            },
        },
    })
</script>
