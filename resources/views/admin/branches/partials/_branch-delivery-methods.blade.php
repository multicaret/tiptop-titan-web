<h3 class="px-3 mb-0 mt-4 text-center">Delivery Methods</h3>
<div class="row px-3">
    <div class="{{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? 'col-md-4' : 'col-md-12' }} mt-2">
        <div class="card card-outline-inverse">
            <h4 class="card-header">
                Tiptop Delivery
                @if($type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
                    <label class="switcher switcher-primary m-3">
                        <input type="checkbox" class="switcher-input" v-model="isTipTopDelivery"
                               @change="isRestaurantDelivery == false ? (isRestaurantDelivery = true) : null"
                               name="has_tip_top_delivery" {{$branch->has_tip_top_delivery ? 'checked' : ''}}>
                        <span class="switcher-indicator">
                                    <span class="switcher-yes">
                                        <span class="ion ion-md-checkmark"></span>
                                    </span>
                                      <span class="switcher-no">
                                        <span class="ion ion-md-close"></span>
                                      </span>
                                </span>
                    </label>
                @endif
            </h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        @component('admin.components.form-group', ['name' => 'minimum_order', 'type' => 'number'])
                            @slot('label', 'Minimum order')
                            @slot('value', is_null($branch->minimum_order) ? 0 : $branch->minimum_order)
                            @slot('attributes',['step'=>1,'min'=>0])
                        @endcomponent
                    </div>
                    <div class="col-md-6 mt-3">
                        @component('admin.components.form-group', ['name' => 'fixed_delivery_fee', 'type' => 'number'])
                            @slot('label', 'Fixed delivery fee')
                            @slot('value', is_null($branch->fixed_delivery_fee) ? 0 : $branch->fixed_delivery_fee)
                            @slot('attributes',['step'=>1,'min'=>0])
                        @endcomponent
                    </div>
                    <div class="mt-3 {{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? 'col-md-7' : 'col-md-6'}}">
                        @component('admin.components.form-group', ['name' => 'under_minimum_order_delivery_fee', 'type' => 'number'])
                            @slot('label', 'Under minimum order delivery fee')
                            @slot('value', is_null($branch->under_minimum_order_delivery_fee) ? 0 : $branch->under_minimum_order_delivery_fee)
                            @slot('attributes',['step'=>1,'min'=>0])
                        @endcomponent
                    </div>
                    <div class="mt-3 {{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? 'col-md-5' : 'col-md-6'}}">
                        @component('admin.components.form-group', ['name' => 'free_delivery_threshold', 'type' => 'number'])
                            @slot('label', 'Free delivery threshold')
                            @slot('value', is_null($branch->free_delivery_threshold) ? 0 : $branch->free_delivery_threshold)
                            @slot('attributes',['step'=>1,'min'=>0])
                        @endcomponent
                    </div>

                    <div class="mt-3 col-md-6">
                        @component('admin.components.form-group', ['name' => 'extra_delivery_fee_per_km', 'type' => 'number'])
                            @slot('label', 'Extra delivery fee per KM')
                            @slot('value', is_null($branch->extra_delivery_fee_per_km) ? 0 : $branch->extra_delivery_fee_per_km)
                            @slot('attributes',['step'=>1,'min'=>0])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
        <div class="col-md-4 mt-2">
            <div class="card card-outline-inverse">
                <h4 class="card-header">Restaurant Delivery
                    <label class="switcher switcher-primary m-3">
                        <input type="checkbox" class="switcher-input"
                               v-model="isRestaurantDelivery"
                               @change="isTipTopDelivery == false ? (isTipTopDelivery = true) : null"
                               name="has_restaurant_delivery" {{$branch->has_restaurant_delivery ? 'checked' : ''}}>
                        <span class="switcher-indicator">
                                    <span class="switcher-yes">
                                        <span class="ion ion-md-checkmark"></span>
                                    </span>
                                      <span class="switcher-no">
                                        <span class="ion ion-md-close"></span>
                                      </span>
                                </span>
                    </label>
                </h4>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mt-3">
                            @component('admin.components.form-group', ['name' => 'restaurant_minimum_order', 'type' => 'number'])
                                @slot('label', 'Minimum order')
                                @slot('value', is_null($branch->restaurant_minimum_order) ? 0 : $branch->restaurant_minimum_order )
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                        <div class="col-md-6 mt-3">
                            @component('admin.components.form-group', ['name' => 'restaurant_fixed_delivery_fee', 'type' => 'number'])
                                @slot('label', 'Fixed delivery fee')
                                @slot('value', is_null($branch->restaurant_fixed_delivery_fee) ? 0 : $branch->restaurant_fixed_delivery_fee)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                        <div
                            class="mt-3 {{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? 'col-md-7' : 'col-md-6'}}">
                            @component('admin.components.form-group', ['name' => 'restaurant_under_minimum_order_delivery_fee', 'type' => 'number'])
                                @slot('label', 'Under minimum order delivery fee')
                                @slot('value', is_null($branch->restaurant_under_minimum_order_delivery_fee) ? 0 : $branch->restaurant_under_minimum_order_delivery_fee)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                        <div
                            class="mt-3 {{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? 'col-md-5' : 'col-md-6'}}">
                            @component('admin.components.form-group', ['name' => 'restaurant_free_delivery_threshold', 'type' => 'number'])
                                @slot('label', 'Free delivery threshold')
                                @slot('value', is_null($branch->restaurant_free_delivery_threshold) ? 0 : $branch->restaurant_free_delivery_threshold)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>

                        <div class="mt-3 col-md-6">
                            @component('admin.components.form-group', ['name' => 'restaurant_extra_delivery_fee_per_km', 'type' => 'number'])
                                @slot('label', 'Restaurant extra delivery fee per KM')
                                @slot('value', is_null($branch->restaurant_extra_delivery_fee_per_km) ? 0 : $branch->restaurant_extra_delivery_fee_per_km)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-2">
            <div class="card card-outline-inverse">
                <h4 class="card-header">Jet Delivery
                    <label class="switcher switcher-primary m-3">
                        <input type="checkbox" class="switcher-input"
                               v-model="isJetDelivery"
                               @change="isJetDelivery == false ? (isJetDelivery = true) : null"
                               name="has_jet_delivery" {{$branch->has_jet_delivery ? 'checked' : ''}}>
                        <span class="switcher-indicator">
                                    <span class="switcher-yes">
                                        <span class="ion ion-md-checkmark"></span>
                                    </span>
                                      <span class="switcher-no">
                                        <span class="ion ion-md-close"></span>
                                      </span>
                                </span>
                    </label>
                </h4>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'jet_minimum_order', 'type' => 'number'])
                                @slot('label', 'Minimum order')
                                @slot('value', is_null($branch->jet_minimum_order) ? 0 : $branch->jet_minimum_order )
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'jet_fixed_delivery_fee', 'type' => 'number'])
                                @slot('label', 'Fixed delivery fee')
                                @slot('value', is_null($branch->jet_fixed_delivery_fee) ? 0 : $branch->jet_fixed_delivery_fee)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'jet_delivery_commission_rate', 'type' => 'number'])
                                @slot('label', 'Commission Rate ')
                                @slot('value', is_null($branch->jet_delivery_commission_rate) ? 0 : $branch->jet_delivery_commission_rate)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>

                        <div class=" col-md-12">
                            @component('admin.components.form-group', ['name' => 'jet_extra_delivery_fee_per_km', 'type' => 'number'])
                                @slot('label', 'Extra delivery fee per KM')
                                @slot('value', is_null($branch->jet_extra_delivery_fee_per_km) ? 0 : $branch->jet_extra_delivery_fee_per_km)
                                @slot('attributes',['step'=>1,'min'=>0])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

</div>
