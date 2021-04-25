@extends('layouts.admin')
@if($coupon->id)
    @section('title', "Editing Coupon")
@else
    @section('title', "Add new")
@endif

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.coupons.index') }}">
            Coupons
        </a>
    </li>
    <li class="breadcrumb-item active">
        @if($coupon->id)
            Editing Coupon
        @else
            Add New Coupon
        @endif
    </li>
@endsection
@section('content')
    <div class="mb-4">
        @if(!is_null($coupon->id))
            <h5>Editing Coupon - {{ $coupon->title }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} Coupon</h5>
        @endif
    </div>
    <form method="post" enctype="multipart/form-data"
          @if(is_null($coupon->id))
          action="{{route('admin.coupons.store')}}"
          @else
          action="{{route('admin.coupons.update',$coupon->id)}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($coupon->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mt-2">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Coupon details</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @component('admin.components.form-group', ['name' => 'name', 'type' => 'text'])
                                    @slot('label', trans('strings.name'))
                                    @slot('value', $coupon->name)
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                @component('admin.components.form-group', ['name' => 'redeem_code', 'type' => 'text'])
                                    @slot('label', 'Redeem code')
                                    @slot('value', $coupon->redeem_code)
                                @endcomponent
                            </div>
                            <div class="col-md-12">
                                @component('admin.components.form-group', ['name' => 'description', 'type' => 'textarea'])
                                    @slot('label', trans('strings.description'))
                                    @slot('attributes',['rows' => 2,])
                                    @slot('value', $coupon->description)
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                <div class="custom-controls-stacked mb-2">
                                    <span>Fixed amount</span>
                                    &nbsp;
                                    <label class="switcher">
                                        <input type="checkbox"
                                               class="switcher-input"
                                               name="discount_by_percentage"
                                               :checked="coupon.discount_by_percentage"
                                               :value="coupon.discount_by_percentage"
                                               @click="discountByPercentage"
                                        >
                                        <span class="switcher-indicator">
                                                    <span class="switcher-yes"></span>
                                                    <span class="switcher-no"></span>
                                                </span>
                                        <span class="switcher-label">Percentage</span>
                                    </label>
                                </div>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span v-if="coupon.discount_by_percentage" class="input-group-text">%</span>
                                        <span v-else
                                              class="input-group-text">{{config('defaults.currency.code')}}</span>
                                    </div>
                                    <input
                                        v-model="coupon.discount_amount"
                                        type="number"
                                        min="0"
                                        class="form-control"
                                        :placeholder="coupon.discount_by_percentage ? 'Discount percentage' : 'Discount amount'"
                                        :step="coupon.discount_by_percentage ? '0.01':'1'"
                                    >
                                </div>
                                @if ($errors->has('discount_amount'))
                                    <span class="form-text text-danger form-control-feedback">
                                            <small>
                                                {{ $errors->first('discount_amount') }}
                                            </small>
                                        </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @component('admin.components.form-group', ['name' => 'max_allowed_discount_amount', 'type' => 'number'])
                                    @slot('label', 'Max allowed discount amount')
                                    @slot('attributes',['required', 'min' => 1])
                                    @slot('value', $coupon->max_allowed_discount_amount)
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                @component('admin.components.form-group', ['name' => 'max_usable_count_by_user', 'type' => 'number'])
                                    @slot('label', 'Usage count by same user')
                                    @slot('attributes',['required', 'min' => 1])
                                    @slot('value', $coupon->max_usable_count_by_user)
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                @component('admin.components.form-group', ['name' => 'min_cart_value_allowed', 'type' => 'number'])
                                    @slot('label', 'Min cart value allowed')
                                    @slot('attributes',['required', 'min' => 1])
                                    @slot('value', $coupon->min_cart_value_allowed)
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                @component('admin.components.form-group', ['name' => 'max_usable_count', 'type' => 'number'])
                                    @slot('label', 'Max usage count')
                                    @slot('attributes',['required', 'min' => 1])
                                    @slot('value', $coupon->max_usable_count)
                                @endcomponent
                            </div>
                            <div class="col-6">
                                @component('admin.components.form-group', ['name' => 'expired_at', 'type' => 'datetime-local'])
                                    @slot('label', 'Expired At')
                                    @slot('value', $coupon->expired_at)
                                @endcomponent
                            </div>
                            <div class="col-4 d-flex align-items-center">
                                <label class="switcher">
                                    <input type="checkbox" class="switcher-input"
                                           value="1"
                                           name="has_free_delivery" {{$coupon->has_free_delivery ? 'checked' : ''}}>
                                    <span class="switcher-indicator">
                                    <span class="switcher-yes">
                                        <span class="ion ion-md-checkmark"></span>
                                    </span>
                                      <span class="switcher-no">
                                        <span class="ion ion-md-close"></span>
                                      </span>
                                </span>
                                    <span class="switcher-label">Is include a delivery fee?</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Selectors</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                @component('admin.components.form-group', ['name' => 'channel', 'type' => 'select'])
                                    @slot('label', trans('strings.channel'))
                                    @slot('options', \App\Models\Coupon::getCouponChannelsArray())
                                    @slot('selected', $coupon->channel)
                                @endcomponent
                            </div>
                            <div class="col-6">
                                @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                    @slot('label', trans('strings.status'))
                                    @slot('options', \App\Models\Coupon::getStatusesArray())
                                    @slot('selected', $coupon->status)
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <input type="hidden" name="discount_amount" :value="coupon.discount_amount">
            <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        new Vue({
            el: '#vue-app',
            data: {
                coupon: @json($coupon),
            },
            methods: {
                discountByPercentage: function () {
                    this.coupon.discount_by_percentage = this.coupon.discount_by_percentage !== true;
                    this.coupon.discount_amount = null
                },
            },
        });
    </script>
@endpush
