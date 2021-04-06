<h3 class="px-3 mb-0 mt-4 text-center">Delivery Methods</h3>
<div class="row px-3">
    <div class="{{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? "col-md-6" : "col-md-12" }} mt-2">
        <div class="card card-outline-inverse">
            <h4 class="card-header">Tiptop Delivery
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
                </label></h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        @component('admin.components.form-group', ['name' => 'minimum_order', 'type' => 'number'])
                            @slot('label', 'Minimum order')
                            @slot('value', is_null($branch->minimum_order) ? 0 : $branch->minimum_order)
                            @slot('attributes',['step'=>1,'min'=>1])
                        @endcomponent
                    </div>
                    <div class="col-md-6 mt-3">
                        @component('admin.components.form-group', ['name' => 'fixed_delivery_fee', 'type' => 'number'])
                            @slot('label', 'Fixed delivery fee')
                            @slot('value', is_null($branch->fixed_delivery_fee) ? 0 : $branch->fixed_delivery_fee)
                            @slot('attributes',['step'=>1,'min'=>1])
                        @endcomponent
                    </div>
                    <div class="col-md-12 mt-3">
                        @component('admin.components.form-group', ['name' => 'under_minimum_order_delivery_fee', 'type' => 'number'])
                            @slot('label', 'Under minimum order delivery fee')
                            @slot('value', is_null($branch->under_minimum_order_delivery_fee) ? 0 : $branch->under_minimum_order_delivery_fee)
                            @slot('attributes',['step'=>1,'min'=>1])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
        <div class="col-md-6 mt-2">
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
                    </label></h4>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mt-3">
                            @component('admin.components.form-group', ['name' => 'restaurant_minimum_order', 'type' => 'number'])
                                @slot('label', 'Minimum order')
                                @slot('value', is_null($branch->restaurant_minimum_order) ? 0 : $branch->restaurant_minimum_order )
                                @slot('attributes',['step'=>1,'min'=>1])
                            @endcomponent
                        </div>
                        <div class="col-md-6 mt-3">
                            @component('admin.components.form-group', ['name' => 'restaurant_fixed_delivery_fee', 'type' => 'number'])
                                @slot('label', 'Fixed delivery fee')
                                @slot('value', is_null($branch->restaurant_fixed_delivery_fee) ? 0 : $branch->restaurant_fixed_delivery_fee)
                                @slot('attributes',['step'=>1,'min'=>1])
                            @endcomponent
                        </div>
                        <div class="col-md-12 mt-3">
                            @component('admin.components.form-group', ['name' => 'restaurant_under_minimum_order_delivery_fee', 'type' => 'number'])
                                @slot('label', 'Under minimum order delivery fee')
                                @slot('value', is_null($branch->restaurant_under_minimum_order_delivery_fee) ? 0 : $branch->restaurant_under_minimum_order_delivery_fee)
                                @slot('attributes',['step'=>1,'min'=>1])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
